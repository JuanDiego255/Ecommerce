<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\InstagramAccount;
use App\Models\TenantInfo;

class InstagramController extends Controller
{
    public function index()
    {
        $account = InstagramAccount::where('is_active', true)->latest()->first();
        return view('admin.instagram.index', compact('account'));
    }

    public function connect()
    {
        $tenantinfo = TenantInfo::first();
        $config = "meta.redirect_uri";
        switch ($tenantinfo->tenant) {
            case "magnoliajoycr":
                $config = "meta.redirect_uri_magnolia";
                break;
            case "solociclismocrc":
                $config = "meta.redirect_uri_sc";
                break;
            default:
                break;
        }
        $appId    = config('meta.app_id');
        $redirect = config($config);

        $scopes = implode(',', [
            'pages_show_list',          // listar páginas del usuario y obtener su page access token
            'instagram_basic',          // leer el ig_user_id vinculado a la página y el username
            'instagram_content_publish', // crear containers y publicar en la cuenta de IG Business
        ]);

        // State aleatorio guardado en sesión para validar en el callback
        // y prevenir ataques CSRF en el flujo OAuth de Meta.
        $state = bin2hex(random_bytes(16));
        session(['meta_oauth_state' => $state]);

        $authUrl = "https://www.facebook.com/{$this->graphVersion()}/dialog/oauth"
            . "?client_id={$appId}"
            . "&redirect_uri=" . urlencode($redirect)
            . "&state={$state}"
            . "&scope=" . urlencode($scopes)
            . "&response_type=code";

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect('/instagram')->with('error', 'Autorización cancelada o denegada.');
        }

        // Validar el state para prevenir CSRF en el flujo OAuth
        $returnedState  = $request->get('state');
        $expectedState  = session('meta_oauth_state');
        session()->forget('meta_oauth_state');

        if (!$returnedState || !$expectedState || !hash_equals($expectedState, $returnedState)) {
            return redirect('/instagram')->with('error', 'Estado OAuth inválido. Intenta conectar nuevamente.');
        }

        $code = $request->get('code');
        if (!$code) {
            return redirect('/instagram')->with('error', 'No se recibió el código de autorización.');
        }

        try {
            $tenantinfo = TenantInfo::first();
            $config = "meta.redirect_uri";
            switch ($tenantinfo->tenant) {
                case "magnoliajoycr":
                    $config = "meta.redirect_uri_magnolia";
                    break;
                case "solociclismocrc":
                    $config = "meta.redirect_uri_sc";
                    break;
                default:
                    break;
            }

            // 1) Intercambiar code por user access token (short-lived)
            $tokenResp = Http::get($this->graphUrl('/oauth/access_token'), [
                'client_id'     => config('meta.app_id'),
                'redirect_uri'  => config($config),
                'client_secret' => config('meta.app_secret'),
                'code'          => $code,
            ]);

            if (!$tokenResp->ok()) {
                return redirect('/instagram')->with('error', 'Error obteniendo token: ' . $tokenResp->body());
            }

            $userAccessToken = $tokenResp->json('access_token');
            if (!$userAccessToken) {
                return redirect('/instagram')->with('error', 'Token inválido recibido.');
            }

            // 2) Intercambiar a long-lived token (~60 días)
            $longResp = Http::get($this->graphUrl('/oauth/access_token'), [
                'grant_type'        => 'fb_exchange_token',
                'client_id'         => config('meta.app_id'),
                'client_secret'     => config('meta.app_secret'),
                'fb_exchange_token' => $userAccessToken,
            ]);

            $longToken = $longResp->ok() ? $longResp->json('access_token') : $userAccessToken;

            // 3) Obtener el facebook_user_id del usuario que autorizó la app.
            //    Necesario para el Data Deletion Callback de Meta.
            $meResp = Http::get($this->graphUrl('/me'), [
                'access_token' => $longToken,
                'fields'       => 'id',
            ]);
            $facebookUserId = $meResp->ok() ? ($meResp->json('id') ?? null) : null;

            // 4) Obtener páginas del usuario
            $pagesResp = Http::get($this->graphUrl('/me/accounts'), [
                'access_token' => $longToken,
                'fields'       => 'id,name,access_token',
            ]);

            if (!$pagesResp->ok()) {
                return redirect('/instagram')->with('error', 'No se pudieron obtener páginas: ' . $pagesResp->body());
            }

            $pages = $pagesResp->json('data') ?? [];
            if (count($pages) < 1) {
                return redirect('/instagram')->with('error', 'No se encontraron páginas de Facebook vinculadas a esta cuenta.');
            }

            // MVP: elegimos la primera página
            $page      = $pages[0];
            $pageId    = $page['id'] ?? null;
            $pageToken = $page['access_token'] ?? null;

            if (!$pageId || !$pageToken) {
                return redirect('/instagram')->with('error', 'No se pudo obtener token de página.');
            }

            // 5) Obtener la cuenta de IG conectada a esa página
            $igResp = Http::get($this->graphUrl("/{$pageId}"), [
                'access_token' => $pageToken,
                'fields'       => 'instagram_business_account',
            ]);

            if (!$igResp->ok()) {
                return redirect('/instagram')->with('error', 'No se pudo obtener la cuenta de Instagram: ' . $igResp->body());
            }

            $igId = data_get($igResp->json(), 'instagram_business_account.id');
            if (!$igId) {
                return redirect('/instagram')->with('error', 'Esta Página no tiene una cuenta de Instagram Business vinculada.');
            }

            // 6) Obtener username de Instagram
            $igInfoResp = Http::get($this->graphUrl("/{$igId}"), [
                'access_token' => $pageToken,
                'fields'       => 'username',
            ]);
            $username = $igInfoResp->ok() ? ($igInfoResp->json('username') ?? null) : null;

            // 7) Guardar cuenta (desactivamos otras para dejar una activa)
            InstagramAccount::query()->update(['is_active' => false]);

            InstagramAccount::create([
                'user_id'                        => auth()->id(),
                'facebook_user_id'               => $facebookUserId,
                'facebook_page_id'               => $pageId,
                'facebook_page_access_token'     => $pageToken,
                'instagram_business_account_id'  => $igId,
                'instagram_username'             => $username,
                'account_type'                   => 'business',
                'is_active'                      => true,
            ]);

            return redirect('/instagram')->with('ok', 'Cuenta de Instagram conectada correctamente.');
        } catch (\Throwable $e) {
            return redirect('/instagram')->with('error', 'Error en conexión: ' . $e->getMessage());
        }
    }

    public function disconnect($id)
    {
        $acc = InstagramAccount::findOrFail($id);

        $acc->update([
            'is_active'                  => false,
            'facebook_page_access_token' => null,
            'token_expires_at'           => null,
        ]);

        return back()->with('ok', 'Cuenta desconectada.');
    }

    private function graphVersion(): string
    {
        return config('meta.graph_version', 'v19.0');
    }

    private function graphUrl(string $path): string
    {
        return "https://graph.facebook.com/" . $this->graphVersion() . $path;
    }
}
