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
        return view('webmail');
    }

    public function test()
    {
        return view('test_component');
    }
}
