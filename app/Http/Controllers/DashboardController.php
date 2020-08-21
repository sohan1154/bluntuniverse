<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Models\BreakingNews;
use App\Models\News;
use App\Models\Category;
use App\Models\Gallery;

class DashboardController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info['breaking_news'] = BreakingNews::count();
        $info['news'] = News::count();
        $info['categories'] = Category::count();
        $info['galleries'] = Gallery::count();

        return view('dashboard/index', compact('info'));
    }
}
