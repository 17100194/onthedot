<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class MeetingsController extends Controller
{
    public function index()
    {
        print_r('im here');
    }

    public function q(Request $request)
    {

        $query = $request->input('search');
        $users = DB::table('users')->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->paginate(10);

        // returns a view and passes the view the list of articles and the original query.
        return view('meetings.search', compact('users', 'query'));
    }

    public function schedule()
    {

    }
}
