<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'phone' => ['required','string','max:20','unique:users,phone'],
            'present_address' => ['required','string','max:500'],
            'password' => ['required','string','min:6','confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'present_address' => $data['present_address'] ?? null,
            'role' => 'user',
            'password' => Hash::make($data['password']),
        ]);

        // Send verification email
        $this->sendVerificationEmail($user);

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    private function sendVerificationEmail($user)
    {
        // create token
        $plainToken = Str::random(64);
        // store hashed token for safety
        $hashed = Hash::make($plainToken);

        // expire existing tokens for the user
        DB::table('email_verifications')->where('user_id', $user->id)->delete();

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'token' => $hashed,
            'expires_at' => Carbon::now()->addMinutes(60),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // send email with plain token
        Mail::to($user->email)->send(new VerificationMail($user, $plainToken));
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function account()
    {
        // Redirect admins to admin dashboard
        $role = strtolower((string) auth()->user()->role);
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('account.index');
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:20','unique:users,phone,'.$user->id],
            'present_address' => ['nullable','string','max:500'],
        ]);
        $user->update($data);
        return back()->with('status','Profile updated');
    }

    public function orders()
    {
        return view('account.orders');
    }

    public function messages()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('account.messages', compact('notifications'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status', 'Password updated successfully');
    }

    // Password Reset Functionality (Users Only)
    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        // Check if user is admin - admins cannot reset password
        $user = User::where('email', $request->email)->first();
        if ($user && strtolower($user->role) === 'admin') {
            return back()->withErrors(['email' => 'Password reset is not available for admin accounts. Please contact support.']);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Check if user is admin - admins cannot reset password
        $user = User::where('email', $request->email)->first();
        if ($user && strtolower($user->role) === 'admin') {
            return back()->withErrors(['email' => 'Password reset is not available for admin accounts. Please contact support.']);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.show')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
