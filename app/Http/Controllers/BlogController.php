<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Models\Blog;
use App\Models\CardBlog;
use App\Models\MedicineResult;
use App\Models\MetaTags;
use App\Models\PersonalUser;
use App\Models\TenantInfo;
use App\Models\Testimonial;
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
        $tenantinfo = TenantInfo::first();
        $blogs = Blog::orderBy('title', 'asc')->simplePaginate(8);
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
        switch ($tenantinfo->kind_business) {
            case (1):
                return view('frontend.blog.carsale.index', compact('blogs'));
                break;
            default:
                return view('frontend.blog.index', compact('blogs'));
                break;
        }
    }
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function indexadmin(Request $request)
    {
        $blogs = Blog::orderBy('title')->get();

        return view('admin.blog.index', compact('blogs'));
    }
    /**

     * Get all the articles of the blog.

     *

     * @param Request $request


     */
    public function showArticles(Request $request, $id, $name_url)
    {
        $tenantinfo = TenantInfo::first();
        $blog = Blog::leftJoin('personal_users', 'blogs.personal_id', 'personal_users.id')
            ->select(
                'blogs.id as id',
                'blogs.body as body',
                'blogs.note as note',
                'blogs.image as image',
                'blogs.video_url as video_url',
                'blogs.title as title',
                'blogs.image as image',
                'blogs.is_project as is_project',
                'blogs.horizontal_images as horizontal_images',
                'blogs.autor as autor',
                'blogs.fecha_post as fecha_post',
                'blogs.name_url as name_url',
                'blogs.title_optional as title_optional',
                'personal_users.id as personal_id',
                'personal_users.name as name',
                'personal_users.body as personal_body',
                'personal_users.image as image_personal'
            )
            ->findOrfail($id);
        $another_blogs = Blog::where('id', '!=', $id)->inRandomOrder()->take(4)->get();
        $fecha_post = $blog->fecha_post;
        $cards = CardBlog::where('blog_id', $id)->take(4)->get();
        $comments = Testimonial::where('approve', 1)->inRandomOrder()->orderBy('name', 'asc')
            ->get();
        $results = MedicineResult::where('blog_id', $id)->take(9)->get();
        $tags = DB::table('article_blogs')
            ->where('blog_id', $id)->join('blogs', 'article_blogs.blog_id', 'blogs.id')
            ->select(
                'blogs.title as blog_title',
                'blogs.autor as autor',
                'blogs.name_url as name_url',
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

        switch ($tenantinfo->kind_business) {
            case (1):
                return view('frontend.blog.carsale.show-articles', compact('tags', 'comments', 'cards', 'results', 'another_blogs', 'id', 'fecha_letter', 'blog'));
                break;
            case (6):
            case (7):
                $another_blogs = Blog::where('id', '!=', $id)->inRandomOrder()->get();
                return view('frontend.av.blog.show-articles',  compact('tags', 'comments', 'cards', 'results', 'another_blogs', 'id', 'fecha_letter', 'blog'));
                break;
            default:
                return view('frontend.blog.show-articles', compact('tags', 'comments', 'cards', 'results', 'another_blogs', 'id', 'fecha_letter', 'blog'));
                break;
        }
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
        $profesionals = PersonalUser::get();
        return view('admin.blog.add', compact('profesionals'));
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
            'name_url' => 'required|string|max:200',
            'image' => 'required|max:10000|mimes:jpeg,png,jpg,ico',
            'horizontal_images' => 'required|max:10000|mimes:jpeg,png,jpg,ico'
        ];
        $mensaje = ["required" => 'El :attribute es requerido'];
        $this->validate($request, $campos, $mensaje);
        $auth = auth()->user()->name;
        date_default_timezone_set('America/Chihuahua');
        $name_url = str_replace(" ", "-", $request->name_url);
        $datetoday = date("Y-m-d", time());
        $blog =  request()->except('_token');
        if ($request->hasFile('image')) {
            $blog['image'] = $request->file('image')->store('uploads', 'public');
        }
        if ($request->hasFile('horizontal_images')) {
            $blog['horizontal_images'] = $request->file('horizontal_images')->store('uploads', 'public');
        }
        $title_optional = $request->title_optional;
        $blog['title_optional'] = $title_optional;
        $blog['personal_id'] = $request->personal_id == "0" ? null : $request->personal_id;
        $blog['autor'] = $auth;
        $blog['is_project'] = $request->filled('is_project') ? 1 : 0;
        $blog['video_url'] = $request->video_url;
        $blog['note'] = $request->note;
        $blog['name_url'] = $name_url;
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
            $title_optional = $request->title_optional;

            $article['blog_id'] = $id;
            $article['meta_keywords'] = $meta_keywords;
            $article['meta_description'] = $meta_description;
            $article['slug'] = $slug;
            ArticleBlog::insert($article);
            DB::commit();
            return redirect('blog-show/' . $id . '/show')->with(['status' => 'Artículo creado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return redirect('blog-show/' . $id . '/show')->with(['status' => $th->getMessage(), 'icon' => 'success']);
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $profesionals = PersonalUser::get();
        $blog = Blog::leftJoin('personal_users', 'blogs.personal_id', 'personal_users.id')
            ->select(
                'blogs.id as id',
                'blogs.body as body',
                'blogs.image as image',
                'blogs.title as title',
                'blogs.horizontal_images as horizontal_images',
                'blogs.autor as autor',
                'blogs.note as note',
                'blogs.is_project as is_project',
                'blogs.fecha_post as fecha_post',
                'blogs.name_url as name_url',
                'blogs.video_url as video_url',
                'blogs.title_optional as title_optional',
                'personal_users.id as personal_id',
                'personal_users.name as name'
            )
            ->findOrfail($id);
        return view('admin.blog.edit', compact('blog', 'profesionals'));
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
        if ($request->hasFile('horizontal_images')) {
            Storage::delete('public/' . $blog->horizontal_images);
            $image_h = $request->file('horizontal_images')->store('uploads', 'public');
            $blog->horizontal_images = $image_h;
        }
        $name_url_mod = str_replace(" ", "-", $request->name_url);
        $blog->name_url = $name_url_mod;
        $blog->video_url = $request->video_url;
        $blog->note = $request->note;
        $blog->title = $request->title;
        $blog->is_project = $request->filled('is_project') ? 1 : 0;
        $blog->title_optional = $request->title_optional;
        $blog->personal_id = $request->personal_id == "0" ? null : $request->personal_id;
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
        return redirect('blog-show/' . $blog_id . '/show')->with(['status' => 'Artículo editado con éxito!', 'icon' => 'success']);
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
    //Metodos para las tarjetas
    /**

     * Get all the articles of the blog.

     *

     * @param Request $request


     */
    public function indexCards($id)
    {
        $cards = DB::table('card_blogs')
            ->where('blog_id', $id)->join('blogs', 'card_blogs.blog_id', 'blogs.id')
            ->select(
                'blogs.title as blog_title',
                'card_blogs.title as title',
                'card_blogs.id as id',
                'card_blogs.description as description',
                'card_blogs.opcional_description as opcional_description',
                'card_blogs.image as image',
            )
            ->get();

        return view('admin.blog.index-cards', compact('cards', 'id'));
    }
    /**

     *redirects to add blog view.

     */
    public function addCard($blog_id)
    {
        return view('admin.blog.add-card', compact('blog_id'));
    }

    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function storeCard(Request $request, $id)
    {
        $campos = [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:10000',
            'image' => 'required|max:10000|mimes:jpeg,png,jpg,ico'
        ];
        $mensaje = ["required" => 'El :attribute es requerido'];
        $this->validate($request, $campos, $mensaje);
        $card_blog = new CardBlog();
        $card_blog->blog_id = $id;
        $card_blog->title = $request->title;
        if ($request->hasFile('image')) {
            $card_blog->image = $request->file('image')->store('uploads', 'public');
        }
        $card_blog->description = $request->description;
        $card_blog->save();

        return redirect('blog-cards/' . $id . '/view-cards')->with(['status' => 'Tarjeta creada con éxito!', 'icon' => 'success']);
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function editCard($id, $blog_id)
    {
        $card = CardBlog::findOrfail($id);
        return view('admin.blog.edit-card', compact('card', 'blog_id'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateCard(Request $request, $id, $blog_id)
    {
        $campos = [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:10000'
        ];
        $mensaje = ["required" => 'El :attribute es requerido'];
        $this->validate($request, $campos, $mensaje);
        $card_blog =  request()->except(['_token', '_method']);
        $card_blog = CardBlog::findOrfail($id);
        if ($request->hasFile('image')) {
            Storage::delete('public/' . $card_blog->image);
            $image = $request->file('image')->store('uploads', 'public');
            $card_blog->image = $image;
        }

        $card_blog->title = $request->title;
        $card_blog->description = $request->description;
        $card_blog->opcional_description = $request->opcional_description;
        $card_blog->update();
        return redirect('blog-cards/' . $blog_id . '/view-cards')->with(['status' => 'Tarjeta actualizada con éxito!', 'icon' => 'success']);
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroyCard($id)
    {
        $card = CardBlog::findOrfail($id);
        if (
            Storage::delete('public/' . $card->image)
        ) {
            CardBlog::destroy($id);
        }

        CardBlog::destroy($id);
        return redirect()->back()->with(['status' => 'Tarjeta eliminada con éxito!', 'icon' => 'success']);
    }
}
