<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB, Illuminate\Support\Facades\Storage, Illuminate\Support\Str, Illuminate\Support\Facades\Mail;

class PhotoController extends Controller
{
    public function create(Album $album)
    {
        abort_if($album->user_id !== auth()->id(), 403);

        $data = [
            'title'=>'Mes Photos MIT - '.config('app.name'),
            'description'=>'Page listant mes photos contenus dans'.config('app.name'),
            'heading'=>$album->title,
            'album'=>$album,
        ];
        return view('photo.create', $data);
    }

    public function store(Album $album, PhotoRequest $request)
    {
        $request->validated();
    }
}
