<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Jobs\ResizePhoto;
use App\Models\Album;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Source;
use App\Models\Tag;
use App\Notifications\PhotoDownloaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB, Illuminate\Support\Facades\Storage, Illuminate\Support\Str, Illuminate\Support\Facades\Mail;
use Image;
use Nette\Schema\ValidationException;
use App\Mail\PhotoDownloaded as MailPhoto;

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
        $photo->load('tags:name,slug', 'album.tags:name,slug', 'album.categories:name,slug', 'sources');

        // dd($photo);
        $tags = collect($photo->tags)->merge(collect($photo->album->tags))->unique();

        $categories = $photo->album->categories;

        $data = [
            'title' => $photo->title.' - '.config('app.name'),
            'description' => $photo->title.'. '.$tags->implode('name', ', ').' '.$categories->implode('name', ', '),
            'photo' => $photo,
            'tags' => $tags,
            'categories' => $categories,
            'heading' => $photo->title,
        ];
        return view('photo.show', $data);
    }

    public function download()
    {
        request()->validate([
            'source' => ['required', 'exists:sources,id'],
        ]);

        $source = Source::findOrFail(request('source'));
        $source->load('photo.album.user');

        abort_if(! $source->photo->active, 403);

        if(auth()->id() !== $source->photo->album->user_id){
            $source->photo->album->user->notify(new PhotoDownloaded($source, $source->photo, auth()->user()));

            Mail::to(auth()->user())->send(new MailPhoto($source, auth()->user()));
        }

       /* $download = $source->photo->downloads()->create([
            'user_id' => auth()->id(),
            'width' => $source->width,
            'height' => $source->height,
            'size' => Storage::size($source->path),
            'ip_address' => request()->ip(),
        ]);*/

        return Storage::download($source->path);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
