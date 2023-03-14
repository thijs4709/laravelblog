<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //deze 3 lijnen zijn niet meer nodig omdat we dit meegeven in de AppServiceProvider.php waardoor ze overal aanspreekbaar zijn en niet alleen op de homepagina in onze backend
//        $usersCount = User::count();
//        $postsCount = Post::count();
//        return view("admin.index",compact('usersCount', 'postsCount'));
        return view("admin.index");
    // 2 andere manieren om je variabelen mee te geven (compact is het makkelijkst
//        return view("admin.index"[
//            "usersCount"=> $usersCount,
//            "postsCount"=> $postsCount,
//            ]);
//        return view('admin.index')->width('usersCount', $usersCount)->with('postCounts',$postsCount);
    }
}
