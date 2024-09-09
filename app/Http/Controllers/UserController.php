<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        return User::search($request->get('search'))
        ->with('posts')->paginate();
    }
}
