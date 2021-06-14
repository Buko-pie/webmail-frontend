<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Session;

class AccountsController extends Controller
{
    public function register(Request $request)
    {
      if(isset($request['first_name'])){
        $new = Users::create([
          'first_name' => $request['first_name'],
          'last_name' => $request['last_name'],
          'email' => $request['email_address'],
          'password' => $request['create_password']
        ]);

        if(isset($new)){
          return response()->json(['message' => 'new account created'], 200);
        }else{
          return response()->json(['message' => 'new account not created'], 400);
        }
      }
    }

    public function logging_in(Request $request)
    {
      if(isset($request['email'])){
        $user = Users::where('email', $request['email'])
        ->where('password', $request['password'])
        ->first();

        if(isset($user)){
          $request->session()->put('user', $user);
          return route('webmail');
        }else{
          return response()->json(['message' => 'No account found'], 400);
        }
      }
    }

    public function upload_profile_pic(Request $request){
      return response()->json(['data' => $request, 'message' => 'bruh'], 200);
    }

    public function logging_out(){
      session()->forget('user');
      Session::flush();
      return route('login');
    }
}
