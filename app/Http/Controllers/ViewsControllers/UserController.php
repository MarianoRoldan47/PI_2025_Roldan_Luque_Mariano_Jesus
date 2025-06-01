<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $usersPendientesAprobar = User::where('is_approved', false)->where('rol', 'Usuario')->count();

        return view('users.index', compact('users', 'usersPendientesAprobar'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|max:9|unique:users,dni',
            'name' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'required|string|max:255',
            'telefono' => 'required|string|max:9',
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:5',
            'localidad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'rol' => 'required|in:Administrador,Usuario',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'fecha_nacimiento' => 'required|date',
            'imagen' => 'nullable|image|max:2048',
            'is_approved' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if (Auth::user() && Auth::user()->rol === 'Administrador') {
            $validated['is_approved'] = true;
            $validated['approved_at'] = now();
        }

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('imagenes/perfiles', 'public');
        }

        User::create($validated);

        Session::flash('status', 'Usuario creado correctamente.');
        Session::flash('status-type', 'success');

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'dni' => ['required', 'string', 'max:9', Rule::unique('users')->ignore($user->id)],
            'name' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'required|string|max:255',
            'telefono' => 'required|string|max:9',
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:5',
            'localidad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'rol' => 'required|in:Administrador,Usuario',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'fecha_nacimiento' => 'required|date',
            'imagen' => 'nullable|image|max:2048',
            'is_approved' => 'boolean',
        ];

        // Si se proporcionó una contraseña, añadir reglas de validación
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($user->imagen) {
                Storage::disk('public')->delete($user->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('imagenes/perfiles', 'public');
        }

        // Si se cambió la contraseña, actualizarla
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        // Si se cambia el estado de aprobación, registrar la fecha
        if (isset($validated['is_approved']) && $validated['is_approved'] && !$user->is_approved) {
            $validated['approved_at'] = now();
        }

        $user->update($validated);

        Session::flash('status', 'Usuario actualizado correctamente.');
        Session::flash('status-type', 'success');

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user)
    {
        // No permitir eliminar al propio usuario
        if ($user->id === Auth::user()->id) {
            Session::flash('status', 'No puedes eliminar tu propio usuario.');
            Session::flash('status-type', 'danger');
            return back();
        }

        // Eliminar imagen si existe
        if ($user->imagen) {
            Storage::disk('public')->delete($user->imagen);
        }

        $user->delete();

        Session::flash('status', 'Usuario eliminado correctamente.');
        Session::flash('status-type', 'success');

        return redirect()->route('users.index');
    }

    public function solicitudes()
    {
        $pendingUsers = User::where('is_approved', false)
            ->where('rol', 'Usuario')
            ->get();

        return view('users.solicitudes', compact('pendingUsers'));
    }

    public function aprobar(User $user)
    {
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        Session::flash('status', 'Usuario aprobado correctamente.');
        Session::flash('status-type', 'success');

        return back();
    }

    public function rechazar(User $user)
    {
        // Opcionalmente podríamos eliminar el usuario o marcarlo como rechazado
        $user->delete();

        Session::flash('status', 'Usuario rechazado y eliminado correctamente.');
        Session::flash('status-type', 'success');

        return back();
    }
}
