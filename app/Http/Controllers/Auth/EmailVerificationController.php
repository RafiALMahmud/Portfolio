<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    protected $expiresMinutes = 60; // token lifetime

    // call this after user registers (or when user requests resend)
    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->firstOrFail();

        // create token
        $plainToken = Str::random(64);
        // store hashed token for safety
        $hashed = Hash::make($plainToken);

        // expire existing tokens for the user
        DB::table('email_verifications')->where('user_id', $user->id)->delete();

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'token' => $hashed,
            'expires_at' => Carbon::now()->addMinutes($this->expiresMinutes),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // send email with plain token (we only store hashed)
        // Verification URL uses plain token
        Mail::to($user->email)->send(new VerificationMail($user, $plainToken));

        return response()->json(['status' => 'sent']);
    }

    // GET /email/verify/{token}
    public function verify($token)
    {
        $record = DB::table('email_verifications')
                    ->where('expires_at', '>', Carbon::now())
                    ->get();

        // We must search for hashed token using Hash::check
        $found = null;
        foreach ($record as $r) {
            if (Hash::check($token, $r->token)) {
                $found = $r;
                break;
            }
        }

        if (!$found) {
            return redirect('/login')->with('error', 'Invalid or expired verification link.');
        }

        $user = User::find($found->user_id);
        if (!$user) {
            return redirect('/login')->with('error', 'User not found.');
        }

        // mark user verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        // delete token record
        DB::table('email_verifications')->where('id', $found->id)->delete();

        return redirect('/login')->with('success', 'Email verified successfully. You can now log in.');
    }
}