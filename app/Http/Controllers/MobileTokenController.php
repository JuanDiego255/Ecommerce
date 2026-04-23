<?php

namespace App\Http\Controllers;

use App\Models\MobileToken;
use Illuminate\Http\Request;

class MobileTokenController extends Controller
{
    public function index()
    {
        $tokens = MobileToken::orderByDesc('created_at')->get();
        return view('admin.mobile_tokens.index', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        ['plain' => $plain, 'hash' => $hash] = MobileToken::generate();

        MobileToken::create([
            'name'    => $request->name,
            'token'   => $hash,
            'is_active' => true,
        ]);

        // Flash plain token once — it will never be shown again.
        return redirect()->route('mobile-tokens.index')
            ->with('generated_token', $plain)
            ->with('generated_name', $request->name);
    }

    public function toggleActive(MobileToken $mobileToken)
    {
        $mobileToken->update(['is_active' => !$mobileToken->is_active]);
        return back()->with('status', 'Token actualizado.');
    }

    public function destroy(MobileToken $mobileToken)
    {
        $mobileToken->delete();
        return back()->with('status', 'Token eliminado.');
    }
}
