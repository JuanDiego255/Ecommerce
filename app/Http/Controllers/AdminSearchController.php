<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 2) return response()->json([]);

        $results = [];
        $like    = '%' . $q . '%';

        // ── Productos ────────────────────────────────────────────────
        $products = DB::table('clothing')
            ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
            ->where(fn($w) => $w->where('clothing.name', 'LIKE', $like)
                               ->orWhere('clothing.code', 'LIKE', $like))
            ->select('clothing.id', 'clothing.name', 'clothing.code',
                     'pivot_clothing_categories.category_id')
            ->groupBy('clothing.id', 'clothing.name', 'clothing.code',
                      'pivot_clothing_categories.category_id')
            ->limit(6)->get();

        foreach ($products as $p) {
            $url = $p->category_id
                ? url('/edit-clothing/' . $p->id . '/' . $p->category_id)
                : url('/add-item/' . $p->id);
            $results[] = [
                'type'  => 'product',
                'icon'  => 'inventory_2',
                'label' => $p->name,
                'sub'   => 'SKU: ' . $p->code,
                'url'   => $url,
            ];
        }

        // ── Pedidos ──────────────────────────────────────────────────
        $buyQuery = DB::table('buys')->limit(4);
        if (is_numeric($q)) {
            $buyQuery->where('id', (int) $q);
        } else {
            $buyQuery->where(fn($w) => $w->where('name', 'LIKE', $like)
                                        ->orWhere('email', 'LIKE', $like));
        }
        foreach ($buyQuery->select('id', 'name', 'total_buy', 'approved')->get() as $b) {
            $statusMap = [0 => 'Pendiente', 1 => 'Aprobado', 2 => 'Completado', 3 => 'Cancelado'];
            $results[] = [
                'type'  => 'order',
                'icon'  => 'receipt_long',
                'label' => 'Pedido #' . $b->id . ($b->name ? ' — ' . $b->name : ''),
                'sub'   => ($statusMap[$b->approved] ?? '') . '  ₡' . number_format($b->total_buy ?? 0),
                'url'   => url('/buy/details/admin/' . $b->id),
            ];
        }

        // ── Categorías ───────────────────────────────────────────────
        $cats = DB::table('categories')
            ->where('name', 'LIKE', $like)
            ->select('id', 'name')->limit(4)->get();
        foreach ($cats as $c) {
            $results[] = [
                'type'  => 'category',
                'icon'  => 'folder',
                'label' => $c->name,
                'sub'   => 'Categoría',
                'url'   => url('/add-item/' . $c->id),
            ];
        }

        return response()->json($results);
    }
}
