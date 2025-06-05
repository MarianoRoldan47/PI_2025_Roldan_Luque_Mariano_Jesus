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
use App\Notifications\SolicitudAprobada;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('name')->get();
        $usersPendientesAprobar = User::where('is_approved', false)->where('rol', 'Usuario')->count();

        return view('vistasPersonalizadas.users.index', compact('users', 'usersPendientesAprobar'));
    }

    public function create()
    {
        return view('vistasPersonalizadas.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => [
                'required',
                'string',
                'max:9',
                'unique:users,dni',
                'regex:/^[0-9]{8}[A-Z]$/',
                'valid_dni'
            ],
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
        ], [
            'dni.regex' => 'El DNI debe contener 8 números seguidos de 1 letra mayúscula.',
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

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user)
    {
        return view('vistasPersonalizadas.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('vistasPersonalizadas.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'dni' => [
                'required',
                'string',
                'max:9',
                Rule::unique('users')->ignore($user->id),
                'regex:/^[0-9]{8}[A-Z]$/',
                'valid_dni'
            ],
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

        $messages = [
            'dni.regex' => 'El DNI debe contener 8 números seguidos de 1 letra mayúscula.',
        ];


        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules, $messages);

        if ($request->hasFile('imagen')) {

            if ($user->imagen) {
                Storage::disk('public')->delete($user->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('imagenes/perfiles', 'public');
        }


        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }


        if (isset($validated['is_approved']) && $validated['is_approved'] && !$user->is_approved) {
            $validated['approved_at'] = now();
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {

        if ($user->id === Auth::user()->id) {
            Session::flash('status', 'No puedes eliminar tu propio usuario.');
            Session::flash('status-type', 'danger');
            return back();
        }


        if ($user->imagen) {
            Storage::disk('public')->delete($user->imagen);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function solicitudes()
    {
        $pendingUsers = User::where('is_approved', false)
            ->where('rol', 'Usuario')
            ->get();

        return view('vistasPersonalizadas.users.solicitudes', compact('pendingUsers'));
    }

    public function aprobar(User $user)
    {
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);
        try {
            $user->notify(new SolicitudAprobada());
        } catch (Exception $e) {
            Log::error('Error al enviar correo de aprobación: ' . $e->getMessage());
        }

        Session::flash('success', 'Usuario aprobado correctamente.');

        return back();
    }

    public function rechazar(User $user)
    {

        $user->delete();

        Session::flash('success', 'Usuario rechazado y eliminado correctamente.');

        return back();
    }


    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $rules = [
            'dni' => [
                'required',
                'string',
                'max:9',
                Rule::unique('users')->ignore($user->id),
                'regex:/^[0-9]{8}[A-Z]$/',
                'valid_dni'
            ],
            'name' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:9',
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:5',
            'localidad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'fecha_nacimiento' => 'required|date',
            'imagen' => 'nullable|image|max:2048',
        ];

        $messages = [
            'dni.regex' => 'El DNI debe contener 8 números seguidos de 1 letra mayúscula.',
        ];

        try {
            $validated = $request->validate($rules, $messages);

            if ($request->hasFile('imagen')) {
                if ($user->imagen) {
                    Storage::disk('public')->delete($user->imagen);
                }

                $validated['imagen'] = $request->file('imagen')->store('imagenes/perfiles', 'public');
            }

            $user->update($validated);

            Session::flash('success', 'Perfil actualizado correctamente.');

            return redirect()->route('perfil');
        } catch (Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage());

            Session::flash('status', 'No se pudo actualizar el perfil. Por favor, inténtalo de nuevo.');
            Session::flash('status-type', 'danger');

            return back()->withInput();
        }
    }
}
