<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BanInfoController extends Controller
{
    public function __construct() {
        $this->middleware('redirectGetBanInfo');
    }

    public function __invoke(Request $request) {
        $banInfo = $request->input('banInfo');
        // dd($banInfo);
        return view('banInfo', compact('banInfo'));
    }
}
