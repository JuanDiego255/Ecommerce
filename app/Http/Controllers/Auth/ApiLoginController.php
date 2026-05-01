<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiLoginController extends Controller
{
    // ─── Login ────────────────────────────────────────────────────────────────

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $this->_formatUser($user),
        ]);
    }

    // ─── Register ─────────────────────────────────────────────────────────────

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'lastname' => ['nullable', 'string', 'max:255'],
                'email'    => ['required', 'email', 'unique:users,email'],
                'phone'    => ['required', 'string', 'max:50'],
                'password' => ['required', 'min:6'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $fullName = trim(($validated['name'] ?? '') . ' ' . ($validated['lastname'] ?? ''));

        $user = User::create([
            'name'      => $fullName,
            'email'     => $validated['email'],
            'telephone' => $validated['phone'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'client',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $this->_formatUser($user),
        ], 201);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Build the user payload Flutter expects:
     * { id, name, lastname, email, phone, image, roles[] }
     */
    public static function _formatUser($user): array
    {
        $nameParts = explode(' ', $user->name ?? '', 2);

        return [
            'id'       => $user->id,
            'name'     => $nameParts[0] ?? ($user->name ?? ''),
            'lastname' => $nameParts[1] ?? '',
            'email'    => $user->email ?? '',
            'phone'    => $user->telephone ?? '',
            'image'    => $user->image ?? null,
            'roles'    => self::_buildRoles((int) ($user->role_as ?? 0)),
        ];
    }

    /**
     * Build roles array based on role_as column (1 = admin, 0 = client).
     */
    public static function _buildRoles(int $roleAs): array
    {
        $clientRole = [
            'id'    => 'client',
            'name'  => 'Cliente',
            'image' => 'https://cdn-icons-png.flaticon.com/512/1077/1077063.png',
            'route' => 'catalog/home',
        ];

        $adminRole = [
            'id'    => 'admin',
            'name'  => 'Administrador',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
            'route' => 'admin/home',
        ];

        return $roleAs === 1 ? [$adminRole, $clientRole] : [$clientRole];
    }
}
