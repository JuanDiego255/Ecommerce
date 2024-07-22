<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\MetaTags;
use App\Models\TenantInfo;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GiftCardController extends Controller
{
    public function index()
    {
        $gifts = GiftCard::where('user_id', Auth::user()->id)->get();

        if (count($gifts) == 0) {
            return redirect('/')->with(['status' => 'No hay tarjetas de regalo compradas!', 'icon' => 'warning']);
        }

        $tags = MetaTags::where('section', 'Mis Compras')->get();
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tenantinfo->title . ' - ' . $tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        $iva = $tenantinfo->iva;

        return view('frontend.mygift-cards', compact('gifts'));
    }
    public function indexAdmin()
    {
        $gifts = GiftCard::all();

        return view('admin.gifts.index', compact('gifts'));
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'by' => 'required|string|max:100',
                'for' => 'required|string|max:10000',
                'email' => 'required|string|max:10000',
                'mount' => 'required|string|max:10000',
                'image' => 'required|max:10000|mimes:jpeg,png,jpg,ico',
            ];
            $mensaje = ['required' => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $gift = new GiftCard();

            if ($request->hasFile('image')) {
                $gift->image = $request->file('image')->store('uploads', 'public');
            }
            if (Auth::check()) {
                $gift->user_id = Auth::user()->id;
            }
            $tenantinfo = TenantInfo::first();
            $gift->by = $request->by;
            $gift->for = $request->for;
            $gift->mount = $request->mount;
            $gift->credit = $request->mount;
            $gift->email = $request->email;
            $gift->code = $this->generateCode();

            $gift->save();
            $gift = GiftCard::where('id', $gift->id)->first();

            $pdf = Pdf::loadView('pdfs.pdf_template', ['gift' => $gift]);
            $pdfFilePath = storage_path('app/Gift_Card_New.pdf');
            $pdf->save($pdfFilePath);
            $this->sendEmailCompra($pdfFilePath, $tenantinfo->email);
            DB::commit();
            return redirect('/')->with(['status' => 'Se realizó la compra de la tarjeta de regalo, una vez que se apruebe se podrá utilizar en el sitio web!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/')->with(['status' => 'No se pudo guardar el profesional', 'icon' => 'error']);
        }
    }
    function generateCode()
    {
        $prefix = 'GC';
        $randomNumbers = str_pad(mt_rand(1, 9999999999999), 13, '0', STR_PAD_LEFT);
        $code = $prefix . $randomNumbers;

        if (GiftCard::where('code', $code)->exists()) {
            $this->generateCode();
        }

        return $code;
    }
    public function approve($id, $approve)
    {
        DB::beginTransaction();
        try {
            $status = 1;
            if ($approve == 1) {
                $status = 0;
            }
            GiftCard::where('id', $id)->update(['approve' => $status]);
            DB::commit();
            if ($status == 1) {
                $gift = GiftCard::where('id', $id)->first();

                // Crear el documento DOCX
                $templatePath = public_path('words/Gift_Card.docx');
                $templateProcessor = new TemplateProcessor($templatePath);
                $templateProcessor->setValue('by', $gift->by);
                $templateProcessor->setValue('for', $gift->for);
                $templateProcessor->setValue('mount', $gift->mount);
                $templateProcessor->setValue('code', $gift->code);
                $docxFilePath = storage_path('app/Gift_Card_New.docx');
                $templateProcessor->saveAs($docxFilePath);

                // Convertir el DOCX a PDF
                $pdf = Pdf::loadView('pdfs.pdf_template', ['gift' => $gift]);

                // Guarda el PDF en el almacenamiento
                $pdfFilePath = storage_path('app/Gift_Card_New.pdf');
                $pdf->save($pdfFilePath);

                // Enviar el PDF por correo
                $this->sendEmail($pdfFilePath, $gift->email);
            }

            return redirect()
                ->back()
                ->with(['status' => 'Se ha cambiado el estado de la tarjeta!', 'icon' => 'success']);
        } catch (Exception $th) {
            dd($th->getMessage());
            DB::rollBack();
        }
    }
    public function sendEmail($templateOutputPath, $email)
    {
        try {
            $tenantinfo = TenantInfo::first();
            // Enviar el correo con el PDF adjunto
            $details = [
                'title' => 'Adquiriste una tarjeta de regalo para canjear en el sitio web - ' . $tenantinfo->title,
            ];

            Mail::send('emails.gift', $details, function ($message) use ($details, $email, $templateOutputPath) {
                $message
                    ->to($email)
                    ->subject($details['title'])
                    ->attach($templateOutputPath, [
                        'as' => 'gift_card.pdf',
                        'mime' => 'application/pdf',
                    ]);
            });

            return true;
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }
    public function sendEmailCompra($templateOutputPath, $email)
    {
        try {
            $tenantinfo = TenantInfo::first();
            // Enviar el correo con el PDF adjunto
            $details = [
                'title' => 'Tarjeta de regalo - Se realizó una venta por medio del sitio web - ' . $tenantinfo->title,
            ];

            Mail::send('emails.gift', $details, function ($message) use ($details, $email, $templateOutputPath) {
                $message
                    ->to($email)
                    ->subject($details['title'])
                    ->attach($templateOutputPath, [
                        'as' => 'gift_card.pdf',
                        'mime' => 'application/pdf',
                    ]);
            });

            return true;
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }
    public function applyCode($code)
    {
        $giftCard = GiftCard::where('code', $code)->where('approve', 1)->where('status', 1)->first();
        return response()->json($giftCard);
    }
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            $gift = GiftCard::findOrfail($id);
            if (Storage::delete('public/' . $gift->image)) {
                GiftCard::destroy($id);
            }
            GiftCard::destroy($id);
            DB::commit();

            return redirect()
                ->back()
                ->with(['status' => 'Se ha eliminado la tarjeta con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
