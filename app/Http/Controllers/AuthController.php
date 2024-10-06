<?php

namespace App\Http\Controllers;

use App\Models\Usuario; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function show()
    {
        try {
            if (Auth::guard('web')->check()) {
                // Si estás usando el guard de 'web'
                return response()->json(Auth::guard('web')->Usuario());
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            \Log::error('Error in UsuarioController@show: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'cedula' => 'required|string|max:12|unique:usuarios',
          
            'password' => 'required|string|min:6|confirmed',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public'); // Guardar la imagen
        }
      
        

        $user = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'cedula' => $request->cedula,
            'role' => $request->role ?? 'Admin', // Si el 'role' no se proporciona, asignar 'usuario'
            'password' => Hash::make($request->password),
            'profile_image' => $imagePath, // Agregar la ruta de la imagen
        ]);

        return response()->json(['message' => 'Usuario registrado con éxito', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }
    
        $user = Auth::guard('web')->user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $user->makeHidden(['password']);
    
        // Asegúrate de construir la URL de la imagen de perfil
        if ($user->profile_image) {
            $user->profile_image = url('storage/' . $user->profile_image);
        }
    
        return response()->json([
            'token' => $token,
            'usuario' => $user,
            'success' => true
        ], 200);
    }
    

    public function updateProfile(Request $request)
{
    $user = $request->user();

    // Validación de los datos del usuario, incluyendo imagen de perfil
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'cedula' => 'required|string|max:12',
        'role' => 'required|string|in:admin,contador,auditor',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
    ]);

    // Si hay una imagen, eliminar la anterior y guardar la nueva
    if ($request->hasFile('profile_image')) {
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
    }

    // Actualizar los datos del usuario
    $user->update($validated);

    // Construir URL de la imagen para devolverla al front-end
    if ($user->profile_image) {
        $user->profile_image = url('storage/' . $user->profile_image);
    }
    
    return response()->json([
        'message' => 'Perfil actualizado correctamente',
        'user' => $user
    ]);
}

    
    
    public function deleteAccount(Request $request)
    {
        $user = $request->user(); // Obtén el usuario autenticado

        if ($user) {
            // Eliminar la imagen si existe
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->delete(); // Elimina el usuario
            return response()->json(['message' => 'Cuenta eliminada con éxito.'], 200);
        }

        return response()->json(['message' => 'Usuario no encontrado.'], 404);
    }
}
