<?php
/**
 * Created by PhpStorm.
 * User: Fahad
 * Date: 8/17/2017
 * Time: 6:20 PM
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $users = DB::table('users')->where('id','!=',Auth::id())->paginate(10);
        return view('admin.dashboard',compact('users'));
    }
}