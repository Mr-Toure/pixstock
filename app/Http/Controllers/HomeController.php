<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

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
        $photos = Photo::with('album.user')->orderByDesc('created_at')->paginate();
        $data = [
            'title'=>config('Photos MIT - '.'app.name'),
            'description'=>'',
            'heading'=>config('app.name'),
            'photos'=>$photos
        ];

        return view('home.index', $data);
    }
}




