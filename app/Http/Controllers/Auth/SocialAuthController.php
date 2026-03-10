<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class SocialAuthController extends Controller
{
    /**
     * Devuelve el driver de Google con SSL deshabilitado en entornos locales.
     * En producción siempre se verifica el certificado SSL.
     */
    private function googleDriver()
    {
        $driver = Socialite::driver('google');

        if (app()->isLocal()) {
            $driver->setHttpClient(new GuzzleClient(['verify' => false]));
        }

        return $driver;
    }

    /**
     * Redirigir al usuario a la pantalla de autenticación de Google.
     */
    public function redirectToGoogle()
    {
        return $this->googleDriver()->redirect();
    }

    /**
     * Manejar la respuesta de Google y autenticar al usuario.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = $this->googleDriver()->user();
        } catch (InvalidStateException $e) {
            // Estado OAuth inválido: el usuario tardó mucho o navegó directamente al callback.
            // La solución es reiniciar el flujo desde /auth/google.
            Log::warning('Google OAuth InvalidStateException: ' . $e->getMessage());

            return redirect()->route('auth.google');
        } catch (Throwable $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());

            $message = config('app.debug')
                ? '[' . class_basename($e) . '] ' . $e->getMessage()
                : 'No se pudo autenticar con Google. Inténtalo de nuevo.';

            return redirect()->route('login')
                ->withErrors(['email' => $message]);
        }

        // 1. Buscar por google_id (usuario ya autenticado antes con Google)
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // 2. Buscar por email (cuenta existente con email/password)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Vincular el google_id a la cuenta existente
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // 3. Crear nueva cuenta con los datos de Google
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password'          => null,
                ]);
            }
        }

        Auth::login($user, remember: true);

        return redirect()->intended(config('fortify.home', '/dashboard'));
    }
}
