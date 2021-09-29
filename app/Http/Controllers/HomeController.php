<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request) //display only page  with data
    {
        $currentPage = request()->query('page', 1);
        $photos = Cache::rememberForever('photos_'.$currentPage, function (){
            return Photo::with('album.user')->orderByDesc('created_at')->paginate();
        });
        $data = [
            'title'=>config('Photos MIT - '.'app.name'),
            'description'=>'',
            'heading'=>config('app.name'),
            'photos'=>$photos
        ];

        return view('home.index', $data);
    }
}




