<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralCategory;
use App\Models\Routine;
use App\Models\RoutineDays;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function mayor($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {         
            if($request->status=="1"){
                User::where('id', $id)->update(['mayor' => 1]);
            }else{
                User::where('id', $id)->update(['mayor' => 0]);
            }
            
            DB::commit();
            return redirect('/users')->with(['status' => 'Se cambio el estado (Al por mayor) para este usuario', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
   
}
