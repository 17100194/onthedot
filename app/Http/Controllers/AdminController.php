<?php
/**
 * Created by PhpStorm.
 * User: Fahad
 * Date: 8/17/2017
 * Time: 6:20 PM
 */

namespace App\Http\Controllers;

use App\Mail\AccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\User;

class AdminController extends Controller
{
    public function index(){
        $users = DB::table('users')->paginate(10);
        return view('admin.dashboard',compact('users'));
    }

    public function addNewUser(Request $request){
        $validation = Validator::make($request->all(), [
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users',
            'type'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $passwordstring = str_random(10);
        $token = str_random(20);
        if ($request->type == 'teacher'){
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'campusid'=>null,
                'type'=>'teacher',
                'avatar'=>'default_instructor.png',
                'password'=>bcrypt($passwordstring),
                'email_token'=>$token
            ]);
            $email = new AccountCreated(new User(['name'=>$request->name,'email_token'=>$token,'password'=>$passwordstring,'email'=>$request->email]));
            Mail::to($request->email)->send($email);
            return response()->json(['success' => '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            <strong>Account created successfully!</strong> Account details have been emailed to the user 
        </div>']);
        }
    }
}