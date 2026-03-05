<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/**
 * Controlador responsável pelo login social via OAuth
 * Suporta múltiplos provedores, como Google e Facebook.
 * 
 * @package App\Http\Controllers\Auth
 */
class SocialController extends Controller
{
    /**
     * Redireciona o usuário para o provedor de autenticação OAuth.
     *
     * @param string $provider Nome do provedor (ex: 'github', 'google').
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Redirecionamento para a página de login do provedor.
     */
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Recebe o callback do provedor OAuth e autentica o usuário local.
     *
     * @param string $provider Nome do provedor (ex: 'github', 'google').
     * @return \Illuminate\Http\RedirectResponse Redirecionamento para a dashboard após login.
     */
    public function callback(string $provider)
    {
        // Obtém os dados do usuário do provedor
        $providerUser = Socialite::driver($provider)->user();

        // Cria ou atualiza o usuário local
        $user = User::updateOrCreate([
            'email' => $providerUser->getEmail()
        ], [
            'name' => $providerUser->getName(),
            'provider_id' => $providerUser->getId(),
            'provider_name' => $provider,
            'provider_avatar' => $providerUser->getAvatar(),
        ]);

        // Realiza login
        Auth::login($user);

        return redirect('/dashboard');
    }
}