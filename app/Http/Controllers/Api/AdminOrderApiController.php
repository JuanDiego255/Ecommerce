<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buy;
use App\Models\BuyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderApiController extends Controller
{
    // ─── List ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $status  = $request->input('status', 'all'); // all|vigente|entregado|cancelado
        $kind    = $request->input('kind', 'all');   // all|web|interna|apartado
        $page    = max(1, (int) $request->input('page', 1));
        $perPage = max(1, (int) $request->input('per_page', 15));

        $query = Buy::leftJoin('users', 'buys.user_id', '=', 'users.id')
            ->select(
                'buys.id',
                DB::raw('COALESCE(buys.name,  users.name,  "Sin nombre") as display_name'),
                DB::raw('COALESCE(buys.email, users.email, "")           as display_email'),
                DB::raw('COALESCE(buys.telephone, users.telephone, "")   as display_telephone'),
                'buys.total_buy',
                'buys.total_delivery',
                'buys.total_iva',
                'buys.approved',
                'buys.delivered',
                'buys.ready_to_give',
                'buys.cancel_buy',
                'buys.kind_of_buy',
                'buys.apartado',
                'buys.monto_apartado',
                'buys.guide_number',
                'buys.detail',
                'buys.created_at'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('buys.name',      'like', "%{$search}%")
                  ->orWhere('users.name',      'like', "%{$search}%")
                  ->orWhere('buys.telephone',  'like', "%{$search}%")
                  ->orWhere('users.telephone', 'like', "%{$search}%")
                  ->orWhere('buys.email',      'like', "%{$search}%")
                  ->orWhere('users.email',     'like', "%{$search}%");
            });
        }

        switch ($status) {
            case 'vigente':
                $query->where('buys.cancel_buy', 0)->where('buys.delivered', 0);
                break;
            case 'entregado':
                $query->where('buys.delivered', 1);
                break;
            case 'cancelado':
                $query->where('buys.cancel_buy', '>', 0);
                break;
        }

        switch ($kind) {
            case 'web':
                $query->where('buys.kind_of_buy', 'V');
                break;
            case 'interna':
                $query->where('buys.kind_of_buy', '!=', 'V')->where('buys.apartado', 0);
                break;
            case 'apartado':
                $query->where('buys.apartado', 1);
                break;
        }

        try {
            $total = DB::table(DB::raw("({$query->toSql()}) as sub"))
                ->mergeBindings($query->getQuery())
                ->count();
        } catch (\Exception $e) {
            $total = 0;
        }

        $items = $query->orderBy('buys.id', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $items,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total'        => $total,
                'last_page'    => max(1, (int) ceil($total / $perPage)),
            ],
        ]);
    }

    // ─── Detail ───────────────────────────────────────────────────────────────

    public function show($id)
    {
        $buy = Buy::where('buys.id', $id)
            ->leftJoin('users', 'buys.user_id', '=', 'users.id')
            ->leftJoin('address_users', function ($join) {
                $join->on('users.id', '=', 'address_users.user_id')
                     ->whereRaw('address_users.id = (SELECT MIN(id) FROM address_users WHERE address_users.user_id = users.id)');
            })
            ->select(
                'buys.id',
                DB::raw('COALESCE(buys.name,  users.name,  "Sin nombre") as display_name'),
                DB::raw('COALESCE(buys.email, users.email, "")           as display_email'),
                DB::raw('COALESCE(buys.telephone, users.telephone, "")   as display_telephone'),
                'buys.total_buy',
                'buys.total_delivery',
                'buys.total_iva',
                'buys.approved',
                'buys.delivered',
                'buys.ready_to_give',
                'buys.cancel_buy',
                'buys.kind_of_buy',
                'buys.apartado',
                'buys.monto_apartado',
                'buys.guide_number',
                'buys.detail',
                'buys.created_at',
                DB::raw('COALESCE(buys.country,      address_users.country,      "") as s_country'),
                DB::raw('COALESCE(buys.province,     address_users.province,     "") as s_province'),
                DB::raw('COALESCE(buys.city,         address_users.city,         "") as s_city'),
                DB::raw('COALESCE(buys.address_two,  address_users.address_two,  "") as s_district'),
                DB::raw('COALESCE(buys.address,      address_users.address,      "") as s_address')
            )
            ->first();

        if (!$buy) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        $items = $this->_fetchItems($id);

        $result = $buy->toArray();
        $result['items'] = $items;

        return response()->json(['success' => true, 'data' => $result]);
    }

    // ─── Quick-view (shipping + items) ────────────────────────────────────────

    public function quickInfo($id)
    {
        $buy = Buy::leftJoin('users', 'buys.user_id', '=', 'users.id')
            ->leftJoin('address_users', function ($join) {
                $join->on('users.id', '=', 'address_users.user_id')
                     ->whereRaw('address_users.id = (SELECT MIN(id) FROM address_users WHERE address_users.user_id = users.id)');
            })
            ->where('buys.id', $id)
            ->select(
                DB::raw('COALESCE(buys.name,  users.name,  "Sin nombre") as name'),
                DB::raw('COALESCE(buys.email, users.email, "")           as email'),
                DB::raw('COALESCE(buys.telephone, users.telephone, "")   as telephone'),
                DB::raw('COALESCE(buys.country,      address_users.country,      "—") as country'),
                DB::raw('COALESCE(buys.province,     address_users.province,     "—") as province'),
                DB::raw('COALESCE(buys.city,         address_users.city,         "—") as city'),
                DB::raw('COALESCE(buys.address_two,  address_users.address_two,  "—") as district'),
                DB::raw('COALESCE(buys.address,      address_users.address,      "—") as address')
            )
            ->first();

        if (!$buy) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        return response()->json([
            'success'  => true,
            'shipping' => $buy,
            'items'    => $this->_fetchItems($id),
        ]);
    }

    // ─── Status toggles ───────────────────────────────────────────────────────

    public function toggleApprove($id)
    {
        try {
            $buy = Buy::findOrFail($id);
            $buy->approved = $buy->approved == 1 ? 0 : 1;
            $buy->save();
            return response()->json(['success' => true, 'approved' => $buy->approved]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleDelivery($id)
    {
        try {
            $buy = Buy::findOrFail($id);
            $buy->delivered = $buy->delivered == 1 ? 0 : 1;
            $buy->save();
            return response()->json(['success' => true, 'delivered' => $buy->delivered]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleReady($id)
    {
        try {
            $buy = Buy::findOrFail($id);
            $buy->ready_to_give = $buy->ready_to_give == 1 ? 0 : 1;
            $buy->save();
            return response()->json(['success' => true, 'ready_to_give' => $buy->ready_to_give]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCancelStatus(Request $request, $id)
    {
        try {
            $status = (int) $request->input('cancel', 0);
            Buy::where('id', $id)->update(['cancel_buy' => $status]);
            if ($status > 0) {
                BuyDetail::where('buy_id', $id)
                    ->where('cancel_item', '!=', 2)
                    ->update(['cancel_item' => $status]);
            }
            return response()->json(['success' => true, 'cancel_buy' => $status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Updates ──────────────────────────────────────────────────────────────

    public function updateGuideNumber(Request $request, $id)
    {
        try {
            $buy = Buy::findOrFail($id);
            $buy->guide_number = $request->input('guide_number', '');
            $buy->delivered    = 1;
            $buy->save();
            return response()->json(['success' => true, 'guide_number' => $buy->guide_number]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateNote(Request $request, $id)
    {
        try {
            $buy = Buy::findOrFail($id);
            $buy->detail = $request->input('detail', '');
            $buy->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addAbono(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $buy   = Buy::findOrFail($id);
            $monto = (float) $request->input('monto', 0);
            $buy->monto_apartado = ((float) ($buy->monto_apartado ?? 0)) + $monto;
            $buy->save();
            DB::commit();
            return response()->json(['success' => true, 'monto_apartado' => $buy->monto_apartado]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        try {
            Buy::destroy($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function _fetchItems($buyId): \Illuminate\Support\Collection
    {
        return BuyDetail::where('buy_details.buy_id', $buyId)
            ->join('clothing', 'buy_details.clothing_id', '=', 'clothing.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')
                     ->whereRaw('product_images.id = (SELECT MIN(id) FROM product_images WHERE product_images.clothing_id = clothing.id)');
            })
            ->select(
                'buy_details.id',
                'clothing.name as product_name',
                'buy_details.quantity',
                'buy_details.total',
                'buy_details.cancel_item',
                DB::raw('IFNULL(product_images.image, "") as image'),
                DB::raw('(
                    SELECT GROUP_CONCAT(CONCAT(a.name, ": ", av.value) SEPARATOR " | ")
                    FROM attribute_value_buys avb
                    JOIN attributes a ON avb.attr_id = a.id
                    JOIN attribute_values av ON avb.value_attr = av.id
                    WHERE avb.buy_detail_id = buy_details.id
                ) as attributes_str')
            )
            ->groupBy(
                'buy_details.id', 'clothing.name', 'buy_details.quantity',
                'buy_details.total', 'buy_details.cancel_item', 'product_images.image'
            )
            ->get()
            ->map(function ($item) {
                $item->image_url = $item->image ? route('file', $item->image) : null;
                $item->attributes = $item->attributes_str
                    ? array_values(array_filter(array_map('trim', explode('|', $item->attributes_str))))
                    : [];
                unset($item->image, $item->attributes_str);
                return $item;
            });
    }
}
