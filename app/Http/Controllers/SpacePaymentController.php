<?php

namespace App\Http\Controllers;

use App\Models\SpaceClient;
use App\Models\SpacePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpacePaymentController extends Controller
{
    public function storeClient(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'payment_type' => 'required|in:one_time,monthly',
            'time_to_pay'  => 'nullable|integer|min:1|max:60',
        ]);

        DB::beginTransaction();
        try {
            SpaceClient::create([
                'name'         => $request->name,
                'payment_type' => $request->payment_type,
                'time_to_pay'  => $request->payment_type === 'monthly'
                                    ? ($request->time_to_pay ?? 1)
                                    : 1,
            ]);
            DB::commit();
            return redirect()->back()
                ->with(['status' => 'Cliente creado con éxito', 'icon' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function destroyClient($id)
    {
        DB::beginTransaction();
        try {
            SpaceClient::destroy($id);
            DB::commit();
            return redirect()->back()
                ->with(['status' => 'Cliente eliminado', 'icon' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'client_id'    => 'required|integer|exists:space_clients,id',
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required|string',
            'description'  => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            SpacePayment::create([
                'client_id'    => $request->client_id,
                'amount'       => $request->amount,
                'payment_date' => $request->payment_date,
                'description'  => $request->description,
            ]);
            DB::commit();
            return redirect()->back()
                ->with(['status' => 'Pago registrado con éxito', 'icon' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function destroyPayment($id)
    {
        DB::beginTransaction();
        try {
            SpacePayment::destroy($id);
            DB::commit();
            return redirect()->back()
                ->with(['status' => 'Pago eliminado', 'icon' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with(['status' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function indexClient($id)
    {
        $client   = SpaceClient::findOrFail($id);
        $payments = SpacePayment::where('client_id', $id)
                        ->orderByDesc('payment_date')
                        ->get();

        $totalPaid   = (float) $payments->sum('amount');
        $lastPayment = $payments->first();

        return view('admin.space.client-pay-id', compact(
            'client', 'payments', 'totalPaid', 'lastPayment'
        ));
    }
}
