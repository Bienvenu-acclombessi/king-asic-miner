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
use Illuminate\Support\Facades\Log;
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
                // Merge guest cart with user cart
                $this->mergeGuestCartWithUserCart($requete);

                if (auth()->user()->admin == 1) {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    return redirect()->intended(route('customer.dashboard'));
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
        return redirect(route('public.home'));
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

    /**
     * Merge guest cart with authenticated user cart
     */
    protected function mergeGuestCartWithUserCart(Request $request)
    {
        // Get guest cart from session
        $guestCartId = $request->session()->get('cart_id');

        if (!$guestCartId) {
            return; // No guest cart to merge
        }

        $guestCart = \App\Models\Orders\Cart::find($guestCartId);

        if (!$guestCart || $guestCart->lines->isEmpty()) {
            // Clean up session
            $request->session()->forget('cart_id');
            return; // No items to merge
        }

        // Get or create user cart
        $currencyId = $this->getDefaultCurrencyId();
        $channelId = $this->getDefaultChannelId();

        $userCart = \App\Models\Orders\Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'currency_id' => $currencyId,
                'channel_id' => $channelId,
            ]
        );

        // Merge cart lines
        DB::beginTransaction();
        try {
            foreach ($guestCart->lines as $guestLine) {
                // Check if similar item exists in user cart
                $existingLine = $this->findSimilarCartLine(
                    $userCart,
                    $guestLine->purchasable_type,
                    $guestLine->purchasable_id,
                    $guestLine->optionValues
                );

                if ($existingLine) {
                    // Merge quantities
                    $existingLine->quantity += $guestLine->quantity;
                    $existingLine->save();
                } else {
                    // Move line to user cart
                    $newLine = $guestLine->replicate();
                    $newLine->cart_id = $userCart->id;
                    $newLine->save();

                    // Copy option values
                    foreach ($guestLine->optionValues as $optionValue) {
                        $newOptionValue = $optionValue->replicate();
                        $newOptionValue->cart_line_id = $newLine->id;
                        $newOptionValue->save();
                    }
                }
            }

            // Delete guest cart
            $guestCart->lines()->delete();
            $guestCart->delete();

            // Clean up session
            $request->session()->forget('cart_id');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error but don't fail login
            \Log::error('Failed to merge guest cart: ' . $e->getMessage());
        }
    }

    /**
     * Find similar cart line in user cart
     */
    protected function findSimilarCartLine($cart, $purchasableType, $purchasableId, $optionValues)
    {
        $cartLines = \App\Models\Orders\CartLine::where('cart_id', $cart->id)
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $purchasableId)
            ->with('optionValues')
            ->get();

        if ($cartLines->isEmpty()) {
            return null;
        }

        // If no options, match first line without options
        if ($optionValues->isEmpty()) {
            return $cartLines->first(function ($line) {
                return $line->optionValues->isEmpty();
            });
        }

        // Find line with exact same options
        foreach ($cartLines as $line) {
            if ($this->cartOptionsMatch($line->optionValues, $optionValues)) {
                return $line;
            }
        }

        return null;
    }

    /**
     * Check if cart option values match
     */
    protected function cartOptionsMatch($lineOptions, $compareOptions)
    {
        if ($lineOptions->count() !== $compareOptions->count()) {
            return false;
        }

        foreach ($compareOptions as $compareOption) {
            $match = $lineOptions->first(function ($lineOption) use ($compareOption) {
                return $lineOption->product_option_id == $compareOption->product_option_id
                    && $lineOption->product_option_value_id == $compareOption->product_option_value_id
                    && $lineOption->custom_value == $compareOption->custom_value;
            });

            if (!$match) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get default currency ID
     */
    protected function getDefaultCurrencyId(): int
    {
        try {
            $currency = DB::table('currencies')->first();
            if (!$currency) {
                $currencyId = DB::table('currencies')->insertGetId([
                    'code' => 'USD',
                    'name' => 'US Dollar',
                    'symbol' => '$',
                    'decimal_places' => 2,
                    'enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $currencyId;
            }
            return $currency->id;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * Get default channel ID
     */
    protected function getDefaultChannelId(): int
    {
        try {
            $channel = DB::table('channels')->first();
            if (!$channel) {
                $channelId = DB::table('channels')->insertGetId([
                    'name' => 'Web Store',
                    'handle' => 'web',
                    'default' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $channelId;
            }
            return $channel->id;
        } catch (\Exception $e) {
            return 1;
        }
    }

}
