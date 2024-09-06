<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', function(){
    $posts = Post::with('user')->orderBy('title')->get();
    return view('posts', ['posts'=>$posts]);
});
