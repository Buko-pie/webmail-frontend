<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Session;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class AccountsController extends Controller {
    public function register(Request $request) {
        if (isset($request['first_name'])) {
            $new = Users::create([
                'first_name' => $request['first_name'],
                'last_name'  => $request['last_name'],
                'email'      => $request['email_address'],
                'password'   => $request['create_password'],
            ]);

            if (isset($new)) {
                return response()->json(['message' => 'new account created'], 200);
            } else {
                return response()->json(['message' => 'new account not created'], 400);
            }
        }
    }

    public function logging_in(Request $request) {
        if (!isset($request['email'])) {
            return;
        }

        $user = Users::where('email', $request['email'])
                     ->where('password', $request['password'])
                     ->first();

        if(!$user){
            return response()->json(['message' => 'No account found'], 400);
        }

        $request->session()->put('user', $user);

        return route('webmail');
    }

    public function upload_profile_pic(Request $request) {
        $user = session()->get('user');

        if (isset($user)) {
            $request['profile_photo']->move(public_path('img/users_profile_photo'), $request['filename']);
            $data_update = Users::find($request['user_id']);

            $data_update->update(['profile_photo' => $request['filename']]);

            if (isset($data_update)) {
                $request->session()->put('user', $data_update);
            } else {
                return response()->json(['message' => 'No account found'], 400);
            }

            return response()->json(['data' => $data_update], 200);
        } else {
            return redirect()->route('login');
        }
    }

    public function logging_out() {
        LaravelGmail::logout();
        session()->forget('user');
        Session::flush();

        return route('webmail');
    }
}
