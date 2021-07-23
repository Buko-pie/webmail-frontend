<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class WebmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    /**
     * Show the Test page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $user = LaravelGmail::user();

      if(isset($user)){
        return view('webmail')->with(['base' => url('/')]);
      }else{
        // return redirect()->to('oauth/gmail');
        return LaravelGmail::redirect();
      }
    }

    public function test()
    {
      
      return view('test_component');
    }

    public function login()
    {
      $routes= [
        'register' => route('register'),
        'logging_in' => route('logging_in'),
      ];
      return view('login')->with(['routes' => $routes]);
    }
}
