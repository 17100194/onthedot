<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class Hello extends Controller
{
    public function index($param) {
        $users = DB::table('users')->get();
        var_dump($users);
    }
}
