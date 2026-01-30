<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuthLog;
use App\Models\UserProfile;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identification' => 'required|string|max:255|unique:user_profiles',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'user_name' => 'required|string|max:255|unique:user_profiles',
                'phone' => 'nullable|string|max:20',
                'crm_id' => 'nullable|string|max:255|unique:user_profiles',
                'position' => 'nullable',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            DB::beginTransaction();

            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Crear perfil de usuario
            $profile = UserProfile::create([
                'user_id' => $user->id,
                'identification' =>$request->identification,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_name' => $request->user_name,
                'phone' => $request->phone,
                'crm_id' => $request->crm_id,
                'position' => $request->position,
            ]);

            DB::commit();

            $token = Auth::login($user);

            // Registrar sesión
            AuthLog::create([
                'user_id' => $user->id,
                'token' => $token,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'is_active' => true,
            ]);

            $user->load('profile');

            return $this->responder
                ->success([
                    'user' => UserTransformer::transform($user),
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60
                ])
                ->message('User successfully registered')
                ->statusCode(201)
                ->respond();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return $this->responder
                ->error('Registration failed', 500)
                ->respond();
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $credentials = $request->only('email', 'password');

            if (!$token = Auth::attempt($credentials)) {
                return $this->responder
                    ->error('Invalid credentials', 401)
                    ->respond();
            }

            // Verificar si el usuario está activo
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return $this->responder
                    ->error('User account is inactive', 403)
                    ->respond();
            }

            // Inactivar todas las sesiones activas del usuario
            AuthLog::where('user_id', Auth::id())
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'logout_at' => now(),
                    'logout_type' => 'expired'
                ]);

            // Crear nueva sesión activa
            AuthLog::create([
                'user_id' => Auth::id(),
                'token' => $token,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'is_active' => true,
            ]);

            return $this->responder
                ->success([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60
                ])
                ->message('Login successful')
                ->respond();

        } catch (Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return $this->responder
                ->error('Login failed', 500)
                ->respond();
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user = Auth::user()->load('profile');
            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('User retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving user: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving user', 500)
                ->respond();
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();

            // Marcar la sesión como cerrada en el log
            $authLog = AuthLog::where('token', $token)
                ->where('is_active', true)
                ->first();

            if ($authLog) {
                $authLog->markAsLoggedOut('manual');
            }

            Auth::logout();

            return $this->responder
                ->success(null)
                ->message('Successfully logged out')
                ->respond();
        } catch (Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return $this->responder
                ->error('Logout failed', 500)
                ->respond();
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = Auth::refresh();
            return $this->responder
                ->success([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60
                ])
                ->message('Token refreshed successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return $this->responder
                ->error('Token refresh failed', 500)
                ->respond();
        }
    }

    /**
     * Force logout a specific session by database.
     *
     * @param  int  $logId
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceLogout($logId)
    {
        try {
            $authLog = AuthLog::find($logId);

            if (!$authLog) {
                return $this->responder
                    ->error('Session not found', 404)
                    ->respond();
            }

            if (!$authLog->is_active) {
                return $this->responder
                    ->error('Session already closed', 400)
                    ->respond();
            }

            $authLog->markAsLoggedOut('forced');

            return $this->responder
                ->success(null)
                ->message('Session closed successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Force logout failed: ' . $e->getMessage());
            return $this->responder
                ->error('Force logout failed', 500)
                ->respond();
        }
    }

    /**
     * Get all active sessions for current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveSessions()
    {
        try {
            $sessions = AuthLog::where('user_id', Auth::id())
                ->where('is_active', true)
                ->orderBy('login_at', 'desc')
                ->get();

            return $this->responder
                ->success($sessions)
                ->message('Active sessions retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving active sessions: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving active sessions', 500)
                ->respond();
        }
    }

    /**
     * Get all sessions history for current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSessionsHistory()
    {
        try {
            $sessions = AuthLog::where('user_id', Auth::id())
                ->orderBy('login_at', 'desc')
                ->get();

            return $this->responder
                ->success($sessions)
                ->message('Sessions history retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving sessions history: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving sessions history', 500)
                ->respond();
        }
    }

    /**
     * Activate a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateUser($userId)
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return $this->responder
                    ->error('User not found', 404)
                    ->respond();
            }

            if ($user->is_active) {
                return $this->responder
                    ->error('User is already active', 400)
                    ->respond();
            }

            $user->activate();

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('User activated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error activating user: ' . $e->getMessage());
            return $this->responder
                ->error('Error activating user', 500)
                ->respond();
        }
    }

    /**
     * Deactivate a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivateUser($userId)
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return $this->responder
                    ->error('User not found', 404)
                    ->respond();
            }

            if (!$user->is_active) {
                return $this->responder
                    ->error('User is already inactive', 400)
                    ->respond();
            }

            $user->deactivate();

            // Cerrar todas las sesiones activas del usuario
            AuthLog::where('user_id', $userId)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'logout_at' => now(),
                    'logout_type' => 'forced'
                ]);

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('User deactivated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deactivating user: ' . $e->getMessage());
            return $this->responder
                ->error('Error deactivating user', 500)
                ->respond();
        }
    }

    /**
     * Update authenticated user data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $user->load('profile');

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('User updated successfully')
                ->respond();

        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating user', 500)
                ->respond();
        }
    }

    /**
     * Update authenticated user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            if (!$profile) {
                return $this->responder
                    ->error('User profile not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'identification' => 'sometimes|required|string|max:255|unique:user_profiles,identification,' . $profile->id,
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'user_name' => 'sometimes|required|string|max:255|unique:user_profiles,user_name,' . $profile->id,
                'phone' => 'nullable|string|max:20',
                'crm_id' => 'nullable|string|max:255|unique:user_profiles,crm_id,' . $profile->id,
                'position' => 'nullable',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $profile->fill($request->only([
                'identification',
                'first_name',
                'last_name',
                'user_name',
                'phone',
                'crm_id',
                'position'
            ]));

            $profile->save();
            $user->load('profile');

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('Profile updated successfully')
                ->respond();

        } catch (Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating profile', 500)
                ->respond();
        }
    }

    /**
     * Get user by ID with profile.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserById($userId)
    {
        try {
            $user = User::with('profile')->find($userId);

            if (!$user) {
                return $this->responder
                    ->error('User not found', 404)
                    ->respond();
            }

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('User retrieved successfully')
                ->respond();

        } catch (Exception $e) {
            Log::error('Error retrieving user: ' . $e->getMessage());
            return $this->responder
                ->error('Error retrieving user', 500)
                ->respond();
        }
    }

    /**
     * Update user data by ID (admin action).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserById(Request $request, $userId)
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return $this->responder
                    ->error('User not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $user->load('profile');

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('User updated successfully')
                ->respond();

        } catch (Exception $e) {
            Log::error('Error updating user by ID: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating user', 500)
                ->respond();
        }
    }

    /**
     * Update user profile by user ID (admin action).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfileById(Request $request, $userId)
    {
        try {
            $user = User::with('profile')->find($userId);

            if (!$user) {
                return $this->responder
                    ->error('User not found', 404)
                    ->respond();
            }

            $profile = $user->profile;

            if (!$profile) {
                return $this->responder
                    ->error('User profile not found', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'identification' => 'sometimes|required|string|max:255|unique:user_profiles,identification,' . $profile->id,
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'user_name' => 'sometimes|required|string|max:255|unique:user_profiles,user_name,' . $profile->id,
                'phone' => 'nullable|string|max:20',
                'crm_id' => 'nullable|string|max:255|unique:user_profiles,crm_id,' . $profile->id,
                'position' => 'nullable',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $profile->fill($request->only([
                'identification',
                'first_name',
                'last_name',
                'user_name',
                'phone',
                'crm_id',
                'position'
            ]));

            $profile->save();
            $user->load('profile');

            return $this->responder
                ->success($user, [UserTransformer::class, 'transform'])
                ->message('Profile updated successfully')
                ->respond();

        } catch (Exception $e) {
            Log::error('Error updating profile by ID: ' . $e->getMessage());
            return $this->responder
                ->error('Error updating profile', 500)
                ->respond();
        }
    }
}
