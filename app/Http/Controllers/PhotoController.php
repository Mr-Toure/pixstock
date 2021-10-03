<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Jobs\ResizePhoto;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB, Illuminate\Support\Facades\Storage, Illuminate\Support\Str, Illuminate\Support\Facades\Mail;
use Image;
use Nette\Schema\ValidationException;

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
        DB::beginTransaction();

        try {
            $photo = $album->photos()->create($request->validated());

            //tags
            $tags = explode(',', $request->tags);
            $tags = collect($tags)->filter(function ($value, $key){
                return $value!=='';
            })->all();
            foreach ($tags as $t){
                $tag = Tag::firstOrCreate(['name'=>trim($t)]);
                $photo->tags()->attach($tag->id);
            }

            //image traitement
            if ($request->file('photo')->isValid()){
                $ext = $request->file('photo')->extension();
                $filename = Str::uuid().'.'.$ext;

                $originalPath = $request->file('photo')->storeAs('photos/'.$photo->album_id, $filename);

                $originalWidth = (int) Image::make($request->file('photo'))->width();
                $originalHeight = (int) Image::make($request->file('photo'))->height();

                $originalSource = $photo->sources()->create([
                    'path' => $originalPath,
                    'url' => Storage::url($originalPath),
                    'size' => Storage::size($originalPath),
                    'width' => $originalWidth,
                    'height' => $originalHeight,
                ]);
                //job
                //ResizePhoto::dispatch($originalSource, $photo, $ext);
                DB::afterCommit( fn() => ResizePhoto::dispatch($originalSource, $photo, $ext));
            }
        } catch (ValidationException $e){
            DB::rollBack();
            dd($e->getErrors());
        }

        DB::commit();

        $success = 'Photo ajoutée avec success';
        $redirect =  redirect(route('photos.create', [$album->slug]));
        return $redirect->withSuccess($success);
    }

    public function show(Photo $photo)
    {

    }
}
