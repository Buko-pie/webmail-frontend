<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      $user = session()->get('user');

      if(isset($user)){
        $routes= [
          'data_route' => route('get_dummy_data'),
          'toggle_route' => route('toggle_dummy_data'),
          'set_many_route' => route('toggle_many_dummy_data'),
          'logging_out' => route('logging_out')
        ];
        return view('test_component')->with(['routes' => $routes]);
      }else{
        return redirect()->route('login');
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
