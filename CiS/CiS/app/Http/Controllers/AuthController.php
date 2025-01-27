<?php

namespace App\Http\Controllers;

use App\Models\PosSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $possession = PosSession::firstOrCreate(
                [
                    'users_id' => $user->id,
                    'Date' => Carbon::now()->toDateString()
                ],
                [
                    'cash_in' => 0,
                    'cash_out' => 0,
                    'session_status' => 'open',
                    'total_income' => 0
                ]
            );

            if (!$possession->wasRecentlyCreated) {
                $possession->update(['session_status' => 'open']);
            }

            return redirect('/')->with('success', 'Berhasil login');
        }

        return redirect()->back()->withErrors('Ops, harap cek email atau password Anda');
    }



    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            PosSession::where('users_id', $user->id)
                ->where('session_status', 'open')
                ->update(['session_status' => 'close']);

            Auth::logout();
        }
        return redirect('/login')->with('success', 'Berhasil logout');
    }
}
