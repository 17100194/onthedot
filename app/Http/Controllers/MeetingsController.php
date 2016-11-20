<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class MeetingsController extends Controller
{
    public function index()
    {

    }

    public function q(Request $request)
    {

        $query = $request->input('search');
        $users = DB::table('users')->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->paginate(10);

        return view('meetings.search', compact('users', 'query'));
    }

    public function schedule()
    {

    }
}
