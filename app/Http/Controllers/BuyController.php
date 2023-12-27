<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\BuyDetail;
use App\Models\MetaTags;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\URL;

class BuyController extends Controller
{
    //
    public function index()
    {
        $buys = Buy::where('user_id', Auth::user()->id)->get();
        if (count($buys) == 0) {
            return redirect('/')->with(['status' => 'No hay compras registradas!', 'icon' => 'warning']);
        }
        $tags = MetaTags::where('section', 'Mis Compras')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.buys', compact('buys'));
    }

    public function indexAdmin()
    {
        $buys = Buy::leftJoin('users', 'buys.user_id', 'users.id')
            ->select(
                'buys.id as id',
                'buys.total_iva as total_iva',
                'buys.total_buy as total_buy',
                'buys.delivered as delivered',
                'buys.approved as approved',
                'buys.created_at as created_at',
                'buys.image as image',
                'users.id as user_id',
                'users.name as name',
                'users.last_name as last_name',
                'users.telephone as telephone',
                'users.email as email',
                'buys.name as name_b',
                'buys.last_name as last_name_b',
                'buys.telephone as telephone_b',
                'buys.email as email_b'
            )
            ->get();

        if (count($buys) == 0) {
            return redirect()->back()->with(['status' => 'No hay compras registradas!', 'icon' => 'warning']);
        }
        return view('admin.buys.index', compact('buys'));
    }

    public function buyDetails($id)
    {
        $buysDetails = BuyDetail::where('buys.user_id', Auth::user()->id)
            ->where('buy_details.buy_id', $id)
            ->join('buys', 'buy_details.buy_id', 'buys.id')
            ->join('clothing', 'buy_details.clothing_id', 'clothing.id')
            ->join('sizes', 'buy_details.size_id', 'sizes.id')
            ->select(

                'clothing.id as id',
                'clothing.name as name',
                'clothing.description as description',
                'buy_details.total as total',
                'buy_details.iva as iva',
                'clothing.image as image',
                'clothing.status as status',
                'sizes.size as size',
                'buy_details.quantity as quantity'

            )->get();
        $tags = MetaTags::where('section', 'Mis Compras')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.detail-buy', compact('buysDetails'));
    }

    public function buyDetailsAdmin($id)
    {
        $buysDetails = BuyDetail::where('buy_details.buy_id', $id)
            ->join('buys', 'buy_details.buy_id', 'buys.id')
            ->join('clothing', 'buy_details.clothing_id', 'clothing.id')
            ->join('sizes', 'buy_details.size_id', 'sizes.id')
            ->select(

                'clothing.id as id',
                'clothing.name as name',
                'clothing.description as description',
                'buy_details.total as total',
                'buy_details.iva as iva',
                'clothing.image as image',
                'clothing.status as status',
                'sizes.size as size',
                'buy_details.quantity as quantity'

            )->get();

        $buysDetailsPerson = Buy::where('buys.id', $id)
            ->leftJoin('users', 'buys.user_id', 'users.id')
            ->select(
                'users.id as user_id',
                'users.address as address',
                'users.address_two as address_two',
                'users.city as city',
                'users.country as country',
                'users.province as province',
                'users.postal_code as postal_code',
                'buys.address as address_b',
                'buys.address_two as address_two_b',
                'buys.city as city_b',
                'buys.country as country_b',
                'buys.province as province_b',
                'buys.postal_code as postal_code_b'

            )->get();
        return view('admin.buys.indexDetail', compact('buysDetails', 'buysDetailsPerson'));
    }

    public function approve($id, $approved)
    {
        DB::beginTransaction();
        try {
            $status = 1;
            if ($approved == 1) {
                $status = 0;
            }
            Buy::where('id', $id)->update(['approved' => $status]);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha cambiado el estado de la compra!', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
        }
    }

    public function delivery($id, $delivery)
    {
        DB::beginTransaction();
        try {
            $status = 1;
            if ($delivery == 1) {
                $status = 0;
            }
            Buy::where('id', $id)->update(['delivered' => $status]);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha cambiado el estado de la entrega!', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
        }
    }
}
