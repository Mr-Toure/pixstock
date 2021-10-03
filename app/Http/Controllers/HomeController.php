<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request) //display only page  with data
    {
        $currentPage = request()->query('page', 1);
        //dd(Photo::with('album')->get());
        $photos = Cache::rememberForever('photos_'.$currentPage, function (){
            return Photo::with('album.user')->orderByDesc('created_at')->paginate();
        });
        $data = [
            'title'=>config('Photos MIT - '.'app.name'),
            'description'=>'Page listant les photos contenus dans'.config('app.name'),
            'heading'=>config('app.name'),
            'photos'=>$photos
        ];

        return view('home.index', $data);
    }
}




