<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SuperAdminCodeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmEmail;
use App\Notifications\NouvelleNotification;


class AuthController extends Controller
{
    public function show_register()
    {
        return view('client.pages.auth.register');
    }
    public function show_login()
    {
        return view('client.pages.auth.login');
    }

    public function login(Request $requete)
    {
        $credentials = $requete->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt(request()->only(['email', 'password']))) {
            $requete->session()->regenerate();

            if (auth()->user()->email_verified_at) {
                if (auth()->user()->admin == 1) {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    return redirect()->intended(route('customer.profil'));
                }
            } else {
                $user = User::find(auth()->user()->id);
                auth()->logout();
                Mail::to($user->email)->send(new ConfirmEmail($user));
                return view('client.pages.auth.email_verified');
            }
        }

        return back()->withErrors([
            'email' => 'Mot de passe ou email incorrect.',
        ])->onlyInput('email');
    }
    public function register(Request $requete)
    {
        $credentials = $requete->validate([
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'last_name' => ['required'],
            'first_name' => ['required'],
        ]);
        $user = User::create([
            'first_name' => $requete->first_name,
            'last_name' => $requete->last_name,
            'email' => $requete->email,
            'telephone' => $requete->telephone,
            'password' => Hash::make($requete->password),
            'confirmation_token' => Str::random(16)
        ]);


        Mail::to($user->email)->send(new ConfirmEmail($user));

        return view('client.pages.auth.email_verified');

        // return redirect('/login');
    }
    public function logout()
    {
        auth()->logout();
        return redirect(route('accueil_view'));
    }

    public function verified_mail() {}

    public function confirmEmail($token)
    {
        $user = User::where('confirmation_token', $token)->firstOrFail();
        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return redirect('/auth/login')->with('success', 'Votre adresse e-mail a été confirmée.');
    }
    public function show_email_verified_information()
    {
        return view('client.pages.auth.email_verified');
    }
    public function view_password_reset()
    {
        return view('client.pages.auth.password_forget');
    }

    public function password_forget(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email',],
        ]);
        // Vérifier si le jeton existe dans la table "password_resets"
        $token = Str::random(60);

        $tokenData = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$tokenData) {
            DB::table('password_reset_tokens')->insert(
                ['email' => $request->email, 'token' => Hash::make($token), 'created_at' => Carbon::now()]
            );
        } else {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->update(['token' => Hash::make($token), 'created_at' => now()]);
        }




        //envoie mail
        $user = User::where('email', $request->email)->first();
        $user->notify(new NouvelleNotification(Hash::make($token)));

        return view('client.pages.auth.request_change_password');
    }
    public function password_reset_token($token)
    {
        // Vérifier si le jeton existe dans la table "password_resets"
        $tokenData = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenData) {
            // Le jeton n'existe pas dans la table
            return redirect()->back()->withErrors(['token' => 'Jeton de réinitialisation de mot de passe invalide']);
        }

        // Vérifier si le jeton a expiré
        if (Carbon::parse($tokenData->created_at)->addMinutes(60)->isPast()) {
            // Le jeton a expiré
            return redirect()->back()->withErrors(['token' => 'Jeton de réinitialisation de mot de passe expiré']);
        }
        return view("client.pages.auth.change_password", ['token' => $token]);
    }

    public function password_change(Request $request)
    {
        $credentials = $request->validate([
            'password' => ['required'],
            'password2' => ['required', 'same:password'],
            'token' => ['required', 'exists:password_reset_tokens,token']
        ]);
        $tokenData = DB::table('password_reset_tokens')->where('token', $request->token)->first();


        $user = User::where('email', $tokenData->email)->firstOrFail();

        $user->password = Hash::make($request->input('password'));
        $user->save();
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        return view('client.pages.auth.change_password_success');
    }

    // code de super admin

}
