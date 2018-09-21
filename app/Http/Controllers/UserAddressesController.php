<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    //
    public function index(Request $request)
    {
        //将登录用户地址注入view
        return view('user_addresses.index',[
            'addresses'=> $request->user()->addresses
        ]);
    }
}
