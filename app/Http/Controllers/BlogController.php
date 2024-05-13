<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Models\Blog;
use App\Models\MetaTags;
use App\Models\TenantInfo;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class BlogController extends Controller
{
    /**

     * Get all the blogs, and the events.

     *

     * @param Request $request


     */
    public function index(Request $request)
    {
        SEOMeta::setTitle('Encuentra todo tipo de información referente a Batsë Eventos.');
        SEOMeta::setDescription('Por qué debe escoger nuestra compañía?');
        $blogs = Blog::simplePaginate(8);
        $tags = MetaTags::where('section', 'Blog')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.blog.index', compact('blogs'));
    }
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function indexadmin(Request $request)
    {
        $blogs = Blog::get();

        return view('admin.blog.index', compact('blogs'));
    }
    /**

     * Get all the articles of the blog.

     *

     * @param Request $request


     */
    public function showArticles(Request $request, $id)
    {
        $blog = Blog::findOrfail($id);
        $another_blogs = Blog::where('id','!=',$id)->take(4)->get();
        $fecha_post = $blog->fecha_post;

        $tags = DB::table('article_blogs')
            ->where('blog_id', $id)->join('blogs', 'article_blogs.blog_id', 'blogs.id')
            ->select(
                'blogs.title as blog_title',
                'blogs.autor as autor',
                'blogs.fecha_post as fecha_post',
                'article_blogs.title as title',
                'article_blogs.id as id',
                'article_blogs.context as context',
                'article_blogs.meta_keywords as meta_keywords',
                'article_blogs.meta_description as meta_description'
            )
            ->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph            
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_description);
        }

        $fecha_event = strtotime($fecha_post);
        $f = DateTime::createFromFormat('!m', date("m", $fecha_event));
        $mes_event = date("m", $fecha_event);
        $dia_event = date("d", $fecha_event);
        $anio_event = date("Y", $fecha_event);
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        if ($mes_event <= 12) {
            $name_month_event = $meses[$mes_event - 1];
        }
        $fecha_letter = $dia_event . ' de ' . $name_month_event . ' del ' . $anio_event;

        return view('frontend.blog.show-articles', compact('tags','another_blogs', 'id', 'fecha_letter', 'blog'));
    }
    /**

     * Get all the articles of the blog.

     *

     * @param Request $request


     */
    public function indexArticles(Request $request, $id)
    {
        $tags = DB::table('article_blogs')
            ->where('blog_id', $id)->join('blogs', 'article_blogs.blog_id', 'blogs.id')
            ->select(
                'blogs.title as blog_title',
                'article_blogs.title as title',
                'article_blogs.id as id',
                'article_blogs.context as context',
                'article_blogs.meta_keywords as meta_keywords',
                'article_blogs.meta_description as meta_description'

            )
            ->get();

        return view('admin.blog.index-article', compact('tags', 'id'));
    }
    /**

     *redirects to add blog view.

     */
    public function agregar()
    {
        return view('admin.blog.add');
    }
    /**

     *redirects to add blog view.

     */
    public function agregarInfo($blog_id)
    {
        return view('admin.blog.add-info', compact('blog_id'));
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        $campos = [
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:10000',
            'image' => 'required|max:10000|mimes:jpeg,png,jpg,ico'
        ];
        $mensaje = ["required" => 'El :attribute es requerido'];
        $this->validate($request, $campos, $mensaje);
        $auth = auth()->user()->name;
        date_default_timezone_set('America/Chihuahua');
        $datetoday = date("Y-m-d", time());
        $blog =  request()->except('_token');
        if ($request->hasFile('image')) {
            $blog['image'] = $request->file('image')->store('uploads', 'public');
        }
        $blog['autor'] = $auth;
        $blog['fecha_post'] = $datetoday;

        Blog::insert($blog);
        return redirect('blog/indexadmin')->with(['status' => 'Blog creado con éxito!', 'icon' => 'success']);
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function storeMoreInfo(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'title' => 'required|string|max:1000',
                'context' => 'required|string|max:10000'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);

            $slug = str_replace(" ", "-", $request->title);

            /*   $porciones = explode("storage/", $request->context, 2);
    
            $prueba = explode(" ", $porciones[1]);
            $ext = explode('"', $prueba[0]);
            $path = $ext[0]; */
            $article =  request()->except('_token');
            $meta_keywords = $request->meta_keywords;
            $meta_description = $request->meta_description;

            $article['blog_id'] = $id;
            $article['meta_keywords'] = $meta_keywords;
            $article['meta_description'] = $meta_description;
            $article['slug'] = $slug;
            ArticleBlog::insert($article);
            DB::commit();
            return redirect('blog/' . $id . '/show')->with(['status' => 'Artículo creado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return redirect('blog/' . $id . '/show')->with(['status' => $th->getMessage(), 'icon' => 'success']);
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $blog = Blog::findOrfail($id);
        return view('admin.blog.edit', compact('blog'));
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function editArticle($id, $blog_id)
    {
        $tag = ArticleBlog::findOrfail($id);
        return view('admin.blog.edit-article', compact('tag', 'blog_id'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function update(Request $request, $id)
    {
        $campos = [
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:10000'
        ];
        $mensaje = ["required" => 'El :attribute es requerido'];
        $this->validate($request, $campos, $mensaje);
        $blog =  request()->except(['_token', '_method']);
        $blog = Blog::findOrfail($id);
        if ($request->hasFile('image')) {
            Storage::delete('public/' . $blog->image);
            $image = $request->file('image')->store('uploads', 'public');
            $blog->image = $image;
        }

        $blog->title = $request->title;
        $blog->body = $request->body;
        $blog->update();
        return redirect('blog/indexadmin')->with(['status' => 'Blog actualizado con éxito!', 'icon' => 'success']);
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateArticle(Request $request, $id, $blog_id)
    {
        $campos = [
            'title' => 'required|string|max:1000',
            'context' => 'required|string|max:10000',
        ];
        $mensaje = ["required" => 'El :attribute es requerido'];
        $this->validate($request, $campos, $mensaje);

        $tag =  request()->except(['_token', '_method']);

        $title = $request->get('title');
        $context = $request->get('context');
        $meta_keywords = $request->meta_keywords;
        $meta_description = $request->meta_description;

        $slug = str_replace(" ", "-", $request->title);

        ArticleBlog::where('id', $id)->update([
            'title' => $title,
            'context' => $context,
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description,
            'slug' => $slug
        ]);
        return redirect('blog/' . $blog_id . '/show')->with(['status' => 'Artículo editado con éxito!', 'icon' => 'success']);
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroy($id)
    {
        $blog = Blog::findOrfail($id);
        $tituloblog = Blog::findOrfail($id);
        $nombre = $tituloblog->titulo;
        if (
            Storage::delete('public/' . $blog->imagen)
        ) {
            Blog::destroy($id);
        }

        Blog::destroy($id);
        return redirect()->back()->with(['status' => 'Blog eliminado con éxito!', 'icon' => 'success']);
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroyArticle($id)
    {
        $tag = ArticleBlog::findOrfail($id);
        $articulo = ArticleBlog::findOrfail($id);
        $nombre = $articulo->title;
        if (
            Storage::delete('public/' . $tag->image)
        ) {
            ArticleBlog::destroy($id);
        }

        ArticleBlog::destroy($id);
        return redirect()->back()->with(['status' => 'Artículo eliminado con éxito!', 'icon' => 'success']);
    }
    /**

     *Upload image at the server

     *

     * @param Request $request


     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $filename = pathinfo($originName, PATHINFO_FILENAME);
            $path = $request->file('upload')->store('uploads', 'public');
            $url = route('file', ['path' => $path]);
            return response()->json(['fileName' => $filename, 'uploaded' => 1, 'url' => $url]);
        }
    }
    public function sendEmail(Request $request)
    {
        try {
            $tenantinfo = TenantInfo::first();
            $email = $tenantinfo->email;
            if ($email) {

                $details = [
                    'title' => 'Solicitud de información - Tema: ' . $request->title,
                    'body' => '---------------------------' . PHP_EOL
                ];

                $details['body'] .= 'Nombre: ' . $request->name . ':' . PHP_EOL;
                $details['body'] .= 'Teléfono: ' . $request->telephone . PHP_EOL;
                $details['body'] .= 'E-mail:  ' . $request->email . PHP_EOL;
                $details['body'] .= '------------------------------' . PHP_EOL;
                $details['body'] .= 'Consulta: ' . $request->question . PHP_EOL;

                // Aquí enviamos el correo sin necesidad de especificar una vista
                Mail::raw($details['body'], function ($message) use ($details, $email) {
                    $message->to($email)
                        ->subject($details['title']);
                });
            }
            return redirect()->back()->with(['status' => 'Hemos recibido la información, en breve te contáctaremos!', 'icon' => 'success']);
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }
}
