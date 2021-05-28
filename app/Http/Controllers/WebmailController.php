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
      $data_route = route('get_dummy_data');
      $toggle_route = route('toggle_dummy_data');

      $routes= [
        'data_route' => $data_route,
        'toggle_route' => $toggle_route
      ];
      return view('test_component')->with(['routes' => $routes]);
    }

    public function test()
    {
      return view('test_component');
    }
}
