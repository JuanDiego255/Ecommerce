<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\Controller;
use App\Models\AddressUser;
use App\Models\Buy;
use App\Models\BuyDetail;
use App\Models\ClothingCategory;
use App\Models\TenantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ClientApiController extends Controller
{
    // ─── Addresses ────────────────────────────────────────────────────────────

    public function addresses(Request $request)
    {
        $user      = $request->user();
        $addresses = AddressUser::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return response()->json(
            $addresses->map(fn ($a) => $this->_formatAddress($a))
        );
    }

    public function storeAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'address'      => ['required', 'string', 'max:191'],
                'neighborhood' => ['required', 'string', 'max:100'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $user = $request->user();

        $addr              = new AddressUser();
        $addr->user_id     = $user->id;
        $addr->address     = $validated['address'];
        $addr->address_two = $validated['neighborhood'];
        $addr->city        = $validated['neighborhood'];
        $addr->country     = '';
        $addr->province    = '';
        $addr->postal_code = '';
        $addr->status      = '0';
        $addr->save();

        return response()->json($this->_formatAddress($addr), 201);
    }

    public function deleteAddress(Request $request, $id)
    {
        $user = $request->user();
        $addr = AddressUser::where('id', $id)->where('user_id', $user->id)->first();

        if (!$addr) {
            return response()->json(['message' => 'Dirección no encontrada'], 404);
        }

        $addr->delete();
        return response()->json(['success' => true]);
    }

    // ─── Orders ───────────────────────────────────────────────────────────────

    public function orders(Request $request)
    {
        $user = $request->user();
        $buys = Buy::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(
            $buys->map(fn ($buy) => $this->_formatOrder($buy))
        );
    }

    public function storeOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'address_id'              => ['required', 'integer'],
                'items'                   => ['required', 'array', 'min:1'],
                'items.*.product_id'      => ['required', 'integer'],
                'items.*.quantity'        => ['required', 'integer', 'min:1'],
                'items.*.price'           => ['required', 'numeric', 'min:0'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $user = $request->user();

        $addr = AddressUser::where('id', $validated['address_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$addr) {
            return response()->json(['message' => 'Dirección no válida'], 422);
        }

        $total = collect($validated['items'])
            ->sum(fn ($item) => $item['price'] * $item['quantity']);

        DB::beginTransaction();
        try {
            $buy                  = new Buy();
            $buy->user_id         = $user->id;
            $buy->address_user_id = $addr->id;
            $buy->address         = $addr->address;
            $buy->address_two     = $addr->address_two ?? '';
            $buy->city            = $addr->city ?? '';
            $buy->province        = $addr->province ?? '';
            $buy->country         = $addr->country ?? '';
            $buy->postal_code     = $addr->postal_code ?? '';
            $buy->total_buy       = $total;
            $buy->total_iva       = 0;
            $buy->total_delivery  = 0;
            $buy->approved        = 0;
            $buy->delivered       = 0;
            $buy->kind_of_buy     = 'A';
            $buy->cancel_buy      = 0;
            $buy->save();

            foreach ($validated['items'] as $item) {
                $detail              = new BuyDetail();
                $detail->buy_id      = $buy->id;
                $detail->clothing_id = $item['product_id'];
                $detail->quantity    = $item['quantity'];
                $detail->total       = $item['price'] * $item['quantity'];
                $detail->iva         = 0;
                $detail->cancel_item = 0;
                $detail->save();
            }

            DB::commit();
            return response()->json($this->_formatOrder($buy->fresh()), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ─── Guest order (no auth required) ──────────────────────────────────────

    public function guestOrder(Request $request)
    {
        // items may arrive as a JSON string (multipart) or a native array (JSON body)
        if (is_string($request->input('items'))) {
            $request->merge(['items' => json_decode($request->input('items'), true) ?? []]);
        }

        try {
            $validated = $request->validate([
                'name'        => ['required', 'string', 'max:191'],
                'email'       => ['required', 'email', 'max:191'],
                'telephone'   => ['required', 'string', 'max:50'],
                'country'     => ['nullable', 'string', 'max:100'],
                'province'    => ['required', 'string', 'max:100'],
                'city'        => ['required', 'string', 'max:100'],
                'address_two' => ['nullable', 'string', 'max:100'],
                'address'     => ['required', 'string', 'max:191'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'image'               => ['nullable', 'image', 'max:5120'],
                'items'               => ['required', 'array', 'min:1'],
                'items.*.product_id'  => ['required', 'integer'],
                'items.*.quantity'    => ['required', 'integer', 'min:1'],
                'items.*.price'       => ['required', 'numeric', 'min:0'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $total = collect($validated['items'])
            ->sum(fn ($item) => $item['price'] * $item['quantity']);

        // If authenticated, link to user; otherwise guest
        $userId = optional($request->user())->id;

        DB::beginTransaction();
        try {
            $buy                 = new Buy();
            $buy->user_id        = $userId;
            $buy->name           = $validated['name'];
            $buy->email          = $validated['email'];
            $buy->telephone      = $validated['telephone'];
            $buy->country        = $validated['country'] ?? 'Costa Rica';
            $buy->province       = $validated['province'];
            $buy->city           = $validated['city'];
            $buy->address_two    = $validated['address_two'] ?? '';
            $buy->address        = $validated['address'];
            $buy->postal_code    = $validated['postal_code'] ?? '';
            $buy->total_buy      = $total;
            $buy->total_iva      = 0;
            $buy->total_delivery = 0;
            $buy->approved       = 0;
            $buy->delivered      = 0;
            $buy->kind_of_buy    = 'A';
            $buy->cancel_buy     = 0;
            if ($request->hasFile('image')) {
                $buy->image = $request->file('image')->store('uploads', 'public');
            }
            $buy->save();

            foreach ($validated['items'] as $item) {
                $detail              = new BuyDetail();
                $detail->buy_id      = $buy->id;
                $detail->clothing_id = $item['product_id'];
                $detail->quantity    = $item['quantity'];
                $detail->total       = $item['price'] * $item['quantity'];
                $detail->iva         = 0;
                $detail->cancel_item = 0;
                $detail->save();

                // Decrement product stock when managed
                $qty = (int) $item['quantity'];
                $clothing = ClothingCategory::find((int) $item['product_id']);
                if ($clothing && $clothing->manage_stock == 1) {
                    ClothingCategory::where('id', $clothing->id)
                        ->update(['stock' => DB::raw("GREATEST(0, stock - {$qty})")]);
                    $remaining = ClothingCategory::where('id', $clothing->id)->value('stock');
                    if ($remaining <= 0) {
                        ClothingCategory::where('id', $clothing->id)->update(['status' => 0]);
                    }
                }
            }

            DB::commit();

            $formatted = $this->_formatOrder($buy->fresh());
            $this->_sendOrderEmail($validated['items'], $total, $validated['email'], $validated['name']);

            return response()->json($formatted, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ─── Profile ──────────────────────────────────────────────────────────────

    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'  => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:50'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $user       = $request->user();
        $user->name = $validated['name'];
        if (!empty($validated['phone'])) {
            $user->telephone = $validated['phone'];
        }
        $user->save();

        return response()->json(ApiLoginController::_formatUser($user));
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function _formatAddress(AddressUser $a): array
    {
        return [
            'id'           => $a->id,
            'address'      => $a->address,
            'neighborhood' => $a->city ?? $a->address_two ?? '',
            'id_user'      => $a->user_id,
        ];
    }

    private function _formatOrder(Buy $buy): array
    {
        $details = BuyDetail::where('buy_id', $buy->id)
            ->join('clothing', 'buy_details.clothing_id', '=', 'clothing.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')
                     ->whereRaw('product_images.id = (
                         SELECT MIN(id) FROM product_images WHERE clothing_id = clothing.id
                     )');
            })
            ->select(
                'buy_details.id',
                'buy_details.clothing_id',
                'buy_details.quantity',
                'buy_details.total',
                'buy_details.created_at',
                'buy_details.updated_at',
                'clothing.name as product_name',
                'clothing.price as product_price',
                'clothing.description as product_description',
                DB::raw('IFNULL(product_images.image, "") as image')
            )
            ->get();

        $status = 'PENDIENTE';
        if ((int) ($buy->cancel_buy ?? 0) > 0) {
            $status = 'CANCELADO';
        } elseif ($buy->delivered) {
            $status = 'DESPACHADO';
        } elseif ($buy->approved) {
            $status = 'APROBADO';
        }

        // Build address from address_user_id or inline fields
        $address = null;
        if (!empty($buy->address_user_id)) {
            $addr = AddressUser::find($buy->address_user_id);
            if ($addr) {
                $address = $this->_formatAddress($addr);
            }
        }
        if (!$address && !empty($buy->address)) {
            $address = [
                'id'           => 0,
                'address'      => $buy->address ?? '',
                'neighborhood' => $buy->city ?? '',
                'id_user'      => $buy->user_id,
            ];
        }

        $orderProducts = $details->map(function ($d) use ($buy) {
            $imageUrl = $d->image ? url('file/' . $d->image) : null;
            return [
                'id_order'   => $buy->id,
                'id_product' => (int) $d->clothing_id,
                'quantity'   => (int) $d->quantity,
                'created_at' => $d->created_at ?? now(),
                'updated_at' => $d->updated_at ?? now(),
                'product'    => [
                    'id'          => (int) $d->clothing_id,
                    'name'        => $d->product_name ?? '',
                    'description' => $d->product_description ?? '',
                    'image1'      => $imageUrl,
                    'image2'      => null,
                    'id_category' => 0,
                    'price'       => (float) $d->product_price,
                    'quantity'    => (int) $d->quantity,
                ],
            ];
        })->values();

        return [
            'id'              => $buy->id,
            'id_user'         => $buy->user_id,
            'id_address'      => $buy->address_user_id ?? 0,
            'status'          => $status,
            'created_at'      => $buy->created_at,
            'updated_at'      => $buy->updated_at,
            'user'            => null,
            'address'         => $address,
            'orderHasProducts'=> $orderProducts,
        ];
    }

    private function _sendOrderEmail(array $items, float $total, string $customerEmail, string $customerName): void
    {
        try {
            $tenantinfo = TenantInfo::first();

            $cartItems = collect($items)->map(function ($item) {
                $clothing = ClothingCategory::find((int) $item['product_id']);
                return (object) [
                    'name'     => $clothing ? $clothing->name : 'Producto',
                    'quantity' => $item['quantity'],
                    'total'    => $item['price'] * $item['quantity'],
                ];
            });

            $storeEmail = $tenantinfo->email ?? null;
            if ($storeEmail) {
                $storeDetails = [
                    'cartItems'   => $cartItems,
                    'total_price' => $total,
                    'delivery'    => 0,
                    'title'       => 'Nuevo pedido desde la app móvil - ' . ($tenantinfo->title ?? ''),
                ];
                Mail::send('emails.sale', $storeDetails, function ($message) use ($storeDetails, $storeEmail) {
                    $message->to($storeEmail)->subject($storeDetails['title']);
                });
            }

            $customerDetails = [
                'cartItems'     => $cartItems,
                'total_price'   => $total,
                'delivery'      => 0,
                'store_name'    => $tenantinfo->title ?? 'Tienda',
                'customer_name' => $customerName,
            ];
            Mail::send('emails.sale-customer', $customerDetails, function ($message) use ($customerDetails, $customerEmail) {
                $message->to($customerEmail)
                    ->subject('Confirmación de tu pedido – ' . $customerDetails['store_name']);
            });
        } catch (\Exception $e) {
            // Email failure must not block the order response
        }
    }
}
