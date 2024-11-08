<?php

namespace App\Http\Controllers;

use App\Models\Usuario; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function index()
    {
        // Obtiene todos los usuarios sin relaciones
        $usuarios = Usuario::all(['id','nombre', 'email', 'cedula', 'empresa_id', 'role']);
    
        // Devuelve la respuesta en formato JSON
        return response()->json($usuarios, 200);
    }
    
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
            'empresa_id' => 'required|exists:empresas,id', // Validar que se proporcione un id de empresa válido
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
        
            'role' => $request->role ?? 'Admin', // Si el 'role' no se proporciona, asignar 'Admin'
            
            'empresa_id' => $request->empresa_id, // Guardar el id de la empresa proporcionada
            'password' => Hash::make($request->password),
            'profile_image' => $imagePath, // Agregar la ruta de la imagen
        ]);
    
        return response()->json(['message' => 'Usuario registrado con éxito', 'user' => $user], 201);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        \Log::info('Intento de inicio de sesión con: ', $credentials);
    
        if (!Auth::guard('web')->attempt($credentials)) {
            \Log::warning('Credenciales inválidas para el usuario: ' . $credentials['email']);
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
    public function updateProfile(Request $request, $id = null)
    {
        // Si no se proporciona un ID, se usa el ID del usuario autenticado
        $user = $id ? Usuario::find($id) : $request->user();
    
        // Verifica si el usuario existe
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
    
        // Validación de los datos del usuario, incluyendo imagen de perfil
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cedula' => 'required|string|max:12',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
            'current_password' => 'required_with:password|string|min:6', // Contraseña actual necesaria si se cambia la contraseña
            'password' => 'nullable|string|min:6|confirmed', // Nueva contraseña
        ]);
    
        // Si se está cambiando la contraseña, verificar la contraseña actual
        if ($request->filled('password')) {
            // Verificar la contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'La contraseña actual es incorrecta.'], 403);
            }
            // Cifrar y actualizar la contraseña
            $user->password = Hash::make($request->password);
        }
    
        // Eliminar la contraseña del array validado antes de actualizar los datos
        unset($validated['password']);
        unset($validated['password_confirmation']);
    
        // Si hay una imagen, eliminar la anterior y guardar la nueva
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }
    
        // Actualizar los datos del usuario, excluyendo la contraseña
        $user->update(array_filter($validated, function ($key) {
            return !in_array($key, ['current_password']);
        }, ARRAY_FILTER_USE_KEY));
    
        // Construir URL de la imagen para devolverla al front-end
        if ($user->profile_image) {
            $user->profile_image = url('storage/' . $user->profile_image);
        }
    
        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user
        ]);
    }
    
    
public function deleteAccount(Request $request, $id = null)
{
    // Intentar encontrar el usuario por ID si se proporciona, o usar el usuario autenticado si no
    $usuario = $id ? Usuario::find($id) : $request->user();

    if ($usuario) {
        // Eliminar la imagen si existe
        if ($usuario->profile_image) {
            Storage::disk('public')->delete($usuario->profile_image);
        }

        $usuario->delete(); // Elimina el usuario
        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);
    }

    return response()->json(['message' => 'Usuario no encontrado.'], 404);
}



    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    
        $response = Password::broker('usuarios')->sendResetLink(
            $request->only('email')
        );
    
        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => trans($response)], 200)
            : response()->json(['message' => trans($response)], 400);
    }

    public function showResetForm(Request $request, $token = null)
    {
        // Aquí puedes redirigir al usuario a la URL de React con el token y el email
        $url = url('http://localhost:5173/ResetPassword/' . $token . '?email=' . urlencode($request->email));
        return redirect($url);
    }

    public function reset(Request $request)
    {
        $request->validate([
           
            
            'password' => 'required|confirmed|min:8',
            
        ]);

        // Intenta restablecer la contraseña
        $status = Password::broker('usuarios')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                \Log::info('Usuario encontrado: ' . $user->email);
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
                
                // Mensaje en consola si se guardó correctamente la nueva contraseña
                \Log::info('Contraseña cambiada correctamente para el usuario: ' . $user->email);
                
                $user->setRememberToken(Str::random(60));
            }
        );
        
        

        // Maneja la respuesta
        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }
}
