<?php

namespace App\Http\Controllers;

use App\Models\MetaTags;
use Illuminate\Http\Request;

class MetaTagsController extends Controller
{
    public function index(Request $request)
    {
        $metatags = MetaTags::get();
        return view('admin.metatags.index', compact('metatags'));
    }
    /**

     *get all the events, and redirects to add activity view.

     */
    public function agregar()
    {

        return view('admin.metatags.agregar');
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        try {

            $tag = new MetaTags();
            $tag->section = $request->section;
            $tag->title = $request->title;
            $tag->meta_keywords = $request->meta_keywords;
            $tag->meta_description = $request->meta_description;
            $tag->meta_og_title = $request->meta_og_title;
            $tag->meta_og_description = $request->meta_og_description;
            $tag->url_canonical = $request->url_canonical;
            $tag->url_image_og = $request->url_image_og;
            $tag->meta_type = $request->meta_type;

            $tag->save();
            return redirect('meta-tags/indexadmin')->with(['status' => 'Se agregó la secciòn efectivamente!', 'icon' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**

     * Redirects to add event view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $metatag = MetaTags::findOrfail($id);
        return view('admin.metatags.edit', compact('metatag'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function update(Request $request, $id)
    {
        try {
            $tag = MetaTags::find($id);
            $tag->section = $request->section;
            $tag->title = $request->title;
            $tag->meta_keywords = $request->meta_keywords;
            $tag->meta_description = $request->meta_description;
            $tag->meta_og_title = $request->meta_og_title;
            $tag->meta_og_description = $request->meta_og_description;
            $tag->url_canonical = $request->url_canonical;
            $tag->url_image_og = $request->url_image_og;
            $tag->meta_type = $request->meta_type;

            $tag->save();
            return redirect('meta-tags/indexadmin')->with(['status' => 'Se actualizó la secciòn efectivamente!', 'icon' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroy($id)
    {
        MetaTags::destroy($id);
        return redirect('meta-tags/indexadmin')->with(['status' => 'Se eliminó la secciòn efectivamente!', 'icon' => 'success']);
    }
}
