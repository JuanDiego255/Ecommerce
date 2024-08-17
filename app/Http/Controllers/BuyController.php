<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\BuyDetail;
use App\Models\Cart;
use App\Models\ClothingCategory;
use App\Models\MetaTags;
use App\Models\SizeCloth;
use App\Models\TenantInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class BuyController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    public function index()
    {
        $buys = Cache::remember('user_buys_' . Auth::user()->id, $this->expirationTime, function () {
            return Buy::where('user_id', Auth::user()->id)->get();
        });

        if (count($buys) == 0) {
            return redirect('/')->with(['status' => 'No hay compras registradas!', 'icon' => 'warning']);
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

        return view('frontend.buys', compact('buys', 'iva'));
    }
    public function indexAdmin()
    {
        $tenantinfo = TenantInfo::first();
        $buys = Cache::remember('buys_data', $this->expirationTime, function () {
            return Buy::leftJoin('users', 'buys.user_id', 'users.id')->select(
                'buys.id as id',
                'buys.total_iva as total_iva',
                'buys.total_buy as total_buy',
                'buys.credit_used',
                'buys.kind_of_buy',                
                'buys.total_delivery as total_delivery',
                'buys.delivered as delivered',
                'buys.approved as approved',
                'buys.created_at as created_at',
                'buys.image as image',
                'users.id as user_id',
                'users.name as name',
                'users.telephone as telephone',
                'users.email as email',
                'buys.name as name_b',
                'buys.telephone as telephone_b',
                'buys.email as email_b',
                'buys.cancel_buy as cancel_buy'
            )->get();
        });
        $iva = $tenantinfo->iva;

        if (count($buys) == 0) {
            return redirect()
                ->back()
                ->with(['status' => 'No hay pedidos registrados!', 'icon' => 'warning']);
        }

        return view('admin.buys.index', compact('buys', 'iva'));
    }
    public function buyDetails($id)
    {
        $tenantinfo = TenantInfo::first();
        $buysDetails = Cache::remember('buys_details_' . Auth::user()->id . '_' . $id, $this->expirationTime, function () use ($id) {
            return BuyDetail::where('buys.user_id', Auth::user()->id)
                ->where('buy_details.buy_id', $id)
                ->join('buys', 'buy_details.buy_id', 'buys.id')
                ->join('clothing', 'buy_details.clothing_id', 'clothing.id')
                ->join('attribute_value_buys', 'buy_details.id', 'attribute_value_buys.buy_detail_id')
                ->join('attributes', 'attribute_value_buys.attr_id', 'attributes.id')
                ->join('attribute_values', 'attribute_value_buys.value_attr', 'attribute_values.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                        SELECT MIN(id) FROM product_images
                        WHERE product_images.clothing_id = clothing.id
                    )');
                })
                ->select(
                    'clothing.id as id',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'buy_details.total as total',
                    'buy_details.iva as iva',
                    'buy_details.id as buy_id',
                    'buy_details.buy_id as buy',
                    'buy_details.cancel_item as cancel_item',
                    'clothing.status as status',
                    'buy_details.quantity as quantity',
                    'buys.approved as approved',
                    'buys.kind_of_buy as kind_of_buy',
                    DB::raw('IFNULL(product_images.image, "") as image'),
                    DB::raw('(
                        SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                        FROM attribute_value_buys
                        JOIN attributes ON attribute_value_buys.attr_id = attributes.id
                        JOIN attribute_values ON attribute_value_buys.value_attr = attribute_values.id
                        WHERE attribute_value_buys.buy_detail_id = buy_details.id
                    ) as attributes_values'),
                )
                ->groupBy('clothing.id','buys.kind_of_buy', 'clothing.name', 'clothing.casa', 'clothing.description', 'buy_details.total', 'buy_details.iva', 'buy_details.id', 'buy_details.buy_id', 'buy_details.cancel_item', 'clothing.status', 'buy_details.quantity', 'buys.approved', 'product_images.image')
                ->get();
        });
        $iva = $tenantinfo->iva;
        $tags = MetaTags::where('section', 'Mis Compras')->get();
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . ' - ' . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        return view('frontend.detail-buy', compact('buysDetails', 'iva'));
    }
    public function buyDetailsAdmin($id)
    {
        $tenantinfo = TenantInfo::first();
        $buysDetails = Cache::remember('buy_details_' . $id, $this->expirationTime, function () use ($id) {
            return BuyDetail::where('buy_details.buy_id', $id)
                ->join('buys', 'buy_details.buy_id', '=', 'buys.id')
                ->join('clothing', 'buy_details.clothing_id', '=', 'clothing.id')
                ->join('attribute_value_buys', 'buy_details.id', 'attribute_value_buys.buy_detail_id')
                ->join('attributes', 'attribute_value_buys.attr_id', 'attributes.id')
                ->join('attribute_values', 'attribute_value_buys.value_attr', 'attribute_values.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                    SELECT MIN(id) FROM product_images
                    WHERE product_images.clothing_id = clothing.id
                )');
                })
                ->leftJoin('address_users', 'buys.user_id', '=', 'address_users.user_id')
                ->select(
                    'clothing.id as id',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'buy_details.total as total',
                    'buy_details.iva as iva',
                    'clothing.status as status',
                    'buy_details.quantity as quantity',
                    'buy_details.cancel_item as cancel_item',
                    'buy_details.id as item_id',
                    'buy_details.buy_id as buy',
                    DB::raw('IFNULL(product_images.image, "") as image'),
                    'address_users.user_id as user_id',
                    'address_users.address as address',
                    'address_users.address_two as address_two',
                    'address_users.city as city',
                    'address_users.country as country',
                    'address_users.province as province',
                    'address_users.postal_code as postal_code',
                    'buys.address as address_b',
                    'buys.address_two as address_two_b',
                    'buys.city as city_b',
                    'buys.country as country_b',
                    'buys.province as province_b',
                    'buys.postal_code as postal_code_b',
                    'buys.kind_of_buy as kind_of_buy',
                    DB::raw('(
                        SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                        FROM attribute_value_buys
                        JOIN attributes ON attribute_value_buys.attr_id = attributes.id
                        JOIN attribute_values ON attribute_value_buys.value_attr = attribute_values.id
                        WHERE attribute_value_buys.buy_detail_id = buy_details.id
                    ) as attributes_values'),
                )
                ->groupBy('clothing.id','buys.kind_of_buy', 'clothing.name', 'clothing.casa', 'clothing.description', 'buy_details.total', 'buy_details.iva', 'buy_details.id', 'buy_details.buy_id', 'buy_details.cancel_item', 'clothing.status', 'buy_details.quantity', 'buys.approved', 'address_users.user_id', 'address_users.address', 'address_users.address_two', 'address_users.city', 'address_users.country', 'address_users.province', 'address_users.postal_code', 'buys.address', 'buys.address_two', 'buys.city', 'buys.country', 'buys.province', 'buys.postal_code', 'product_images.image')
                ->get();
        });
        $iva = $tenantinfo->iva;
        $tenant = $tenantinfo->tenant;

        return view('admin.buys.indexDetail', compact('buysDetails', 'iva', 'tenant'));
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
            return redirect()
                ->back()
                ->with(['status' => 'Se ha cambiado el estado de la compra!', 'icon' => 'success']);
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
            return redirect()
                ->back()
                ->with(['status' => 'Se ha cambiado el estado de la entrega!', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
        }
    }
    public function cancelBuy($id, $cancel_buy, Request $request)
    {
        DB::beginTransaction();
        try {
            $status = 1;
            $action = $request->action;
            if ($cancel_buy == 1) {
                if ($action != null) {
                    if ($action == 0) {
                        $status = 0;
                    } else {
                        $status = 2;
                    }
                } else {
                    $status = 0;
                }
            }
            Buy::where('id', $id)->update(['cancel_buy' => $status]);

            BuyDetail::where('buy_id', $id)
                ->where('cancel_item', '!=', 2)
                ->update(['cancel_item' => $status]);

            DB::commit();
            switch ($status) {
                case 1:
                    $status_desc = 'Proceso de cancelación';
                    break;
                case 2:
                    $status_desc = 'Cancelada';
                    break;
                default:
                    $status_desc = 'Vigente';
            }
            return redirect()
                ->back()
                ->with(['status' => 'Proceso de compra: ' . $status_desc, 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
        }
    }
    public function cancelBuyItem($id, $cancel_item, Request $request)
    {
        DB::beginTransaction();
        try {
            $buy = $request->buy;

            $status = 1;
            $action = $request->action;
            if ($cancel_item == 1) {
                if ($action != null) {
                    if ($action == 0) {
                        $status = 0;
                    } else {
                        $status = 2;
                    }
                } else {
                    $status = 0;
                }
            }
            BuyDetail::where('id', $id)->update(['cancel_item' => $status]);
            if ($status == 2) {
                $buysDetails = BuyDetail::where('id', $id)->first();
                $total_det = $buysDetails->total;
                $iva_det = $buysDetails->iva;
                $buys = Buy::where('id', $buy)->first();
                $total_buy = $buys->total_buy;
                $iva_buy = $buys->total_iva;
                $total_buy = $total_buy - $total_det;
                $iva_buy = $iva_buy - $iva_det;
                Buy::where('id', $buy)->update(['total_buy' => $total_buy, 'total_iva' => $iva_buy]);
            }

            if (count(BuyDetail::where('buy_id', $buy)->where('cancel_item', 0)->get()) == 0) {
                Buy::where('id', $buy)->update(['cancel_buy' => $status]);
                DB::commit();
                return redirect('buys')->with(['status' => 'Se ha cancelado el artículo, y la compra!', 'icon' => 'success']);
            }
            DB::commit();
            switch ($status) {
                case 1:
                    $status_desc = 'Proceso de cancelación';
                    break;
                case 2:
                    $status_desc = 'Cancelada';
                    break;
                default:
                    $status_desc = 'Vigente';
            }
            return redirect()
                ->back()
                ->with(['status' => 'Proceso de artículo: ' . $status_desc, 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
        }
    }
    public function indexTotalBuys()
    {
        $tenantinfo = TenantInfo::first();
        $buys = Cache::remember('buys_data', $this->expirationTime, function () {
            return Buy::leftJoin('users', 'buys.user_id', 'users.id')->leftJoin('buy_details', 'buys.id', 'buy_details.buy_id')->select('buys.id as id', 'buys.total_iva as total_iva', 'buys.total_buy as total_buy', 'buys.kind_of_buy as kind_of', 'buys.total_delivery as total_delivery', 'buys.credit_used as credit_used', 'buys.created_at as created_at', DB::raw('sum(buy_details.quantity) as details_count'))->groupBy('buys.id', 'buys.kind_of_buy', 'buys.credit_used', 'buys.total_iva', 'buys.total_buy', 'buys.total_Delivery', 'buys.created_at')->get();
        });
        $iva = $tenantinfo->iva;
        $totalEnvio = 0;
        $totalPrecio = 0;
        $totalVentas = 0;
        $totalDetails = 0;
        $totalIva = 0;

        if (count($buys) == 0) {
            return redirect('/new-buy')->with(['status' => 'No hay ventas en ese rango de fechas!', 'icon' => 'warning']);
        } else {
            $totalPrecio = $buys->sum('total_buy');
            $totalEnvio = $buys->sum('total_delivery');
            $totalDetails = $buys->sum('details_count');
            $totalIva = $buys->sum('total_iva');
            $totalVentas = count($buys);
        }

        $totalEnvio = $buys->sum('total_delivery');
        return view('admin.buys.index-total', compact('buys', 'iva', 'totalIva', 'totalPrecio', 'totalEnvio', 'totalVentas', 'totalDetails'));
    }
    public function indexBuy()
    {
        $tenantinfo = TenantInfo::first();
        $clothings = ClothingCategory::where('status', 1)
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images
                                WHERE product_images.clothing_id = clothing.id
                            )');
            })
            ->select(
                'clothing.id as id',
                'clothing.name as name',
                'clothing.code as code',
                DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
            )
            ->get();
        $cart_items = Cache::remember('cart_items', $this->expirationTime, function () {
            $cart_items = Cart::where('carts.user_id', null)
                ->where('carts.session_id', null)
                ->where('carts.sold', 0)
                ->join('attribute_value_cars', 'carts.id', 'attribute_value_cars.cart_id')
                ->join('attributes', 'attribute_value_cars.attr_id', 'attributes.id')
                ->join('attribute_values', 'attribute_value_cars.value_attr', 'attribute_values.id')
                ->where('stocks.price', '!=', 0)
                ->leftJoin('stocks', function ($join) {
                    $join->on('carts.clothing_id', '=', 'stocks.clothing_id')->on('attribute_value_cars.attr_id', '=', 'stocks.attr_id')->on('attribute_value_cars.value_attr', '=', 'stocks.value_attr');
                })
                ->join('clothing', 'carts.clothing_id', 'clothing.id')
                ->leftJoin('product_images', function ($join) {
                    $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images
                                WHERE product_images.clothing_id = clothing.id
                            )');
                })
                ->select(
                    'clothing.id as id',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.code as code',
                    'clothing.description as description',
                    'clothing.mayor_price as mayor_price',
                    'clothing.discount as discount',
                    'clothing.status as status',
                    'carts.quantity as quantity',
                    'carts.id as cart_id',
                    'attributes.name as name_attr',
                    'attribute_values.value as value',
                    'stocks.price as price',
                    'stocks.stock as stock',
                    DB::raw('(
                            SELECT GROUP_CONCAT(CONCAT(attributes.name, ": ", attribute_values.value) SEPARATOR ", ")
                            FROM attribute_value_cars
                            JOIN attributes ON attribute_value_cars.attr_id = attributes.id
                            JOIN attribute_values ON attribute_value_cars.value_attr = attribute_values.id
                            WHERE attribute_value_cars.cart_id = carts.id
                        ) as attributes_values'),
                    DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
                )
                ->groupBy('clothing.id', 'clothing.name', 'clothing.casa', 'clothing.code', 'clothing.description', 'stocks.price', 'stocks.stock', 'clothing.mayor_price', 'attributes.name', 'attribute_values.value', 'clothing.status', 'clothing.discount', 'carts.quantity', 'carts.id', 'product_images.image')
                ->get();
            // Resto del código para obtener los artículos del carrito para usuarios autenticados
            return $cart_items;
        });

        $tags = MetaTags::where('section', 'Carrito')->get();
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . ' - ' . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        $name = Auth::user()->name ?? null;

        $cloth_price = 0;
        $you_save = 0;
        foreach ($cart_items as $item) {
            $precio = $item->price;
            $descuentoPorcentaje = $item->discount;
            // Calcular el descuento
            $descuento = ($precio * $descuentoPorcentaje) / 100;

            $you_save += $descuento * $item->quantity;
            // Calcular el precio con el descuento aplicado
            $precioConDescuento = $precio - $descuento;
            $cloth_price += $precioConDescuento * $item->quantity;
        }

        $iva = $cloth_price * $tenantinfo->iva;

        $iva_tenant = $tenantinfo->iva;
        $total_price = $cloth_price + $iva;

        return view('admin.buys.buys', compact('cart_items', 'clothings', 'iva_tenant', 'name', 'cloth_price', 'iva', 'total_price', 'you_save'));
    }
    public function sizeByCloth(Request $request)
    {
        $code = $request->code;
        $cloth_check = ClothingCategory::where('code', $code)->first();
        if ($cloth_check) {
            $result = DB::table('stocks as s')
                ->where('s.clothing_id', $cloth_check->id)
                ->join('attributes as a', 's.attr_id', '=', 'a.id')
                ->join('attribute_values as v', 's.value_attr', '=', 'v.id')
                ->select('a.name as columna_atributo', 'a.id as attr_id', 's.clothing_id as clothing_id', DB::raw('GROUP_CONCAT(v.value ORDER BY v.value ASC SEPARATOR "/") as valores'), DB::raw('GROUP_CONCAT(v.id ORDER BY v.value ASC SEPARATOR "/") as ids'), DB::raw('GROUP_CONCAT(s.stock ORDER BY v.value ASC SEPARATOR "/") as stock'))
                ->groupBy('a.name', 'a.id', 's.clothing_id')
                ->get();
            return response()->json(['status' => 'success', 'results' => $result]);
        } else {
            return response()->json(['status' => 'No existe ningún producto con el código digitado', 'icon' => 'warning']);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            $order = Buy::findOrfail($id);
            if (Storage::delete('public/' . $order->image)) {
                Buy::destroy($id);
            }
            Buy::destroy($id);
            DB::commit();

            $buys = Buy::all();

            if (count($buys) == 0) {
                return redirect('/categories')->with(['status' => 'No hay pedidos registrados!', 'icon' => 'warning']);
            }

            return redirect('/buys-admin')->with(['status' => 'Se ha eliminado el pedido con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
