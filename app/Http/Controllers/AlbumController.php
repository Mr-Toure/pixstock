<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Models\album;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //liste des albums de l'utisateur
    {
        $albums = auth()->user()->albums()
            ->with('photos', fn ($query) => $query->withoutGlobalScope('active')->orderByDesc('created_at'))
            ->orderByDesc('updated_at')
            ->paginate();

        $data = [
            'title'=>'Mes Album MIT - '.config('app.name'),
            'description'=>'Page listant mes Albums contenus dans'.config('app.name'),
            'heading'=>'Mes Albums',
            'albums'=>$albums,
        ];
        return view('album.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title'=> $description = $heading = 'Créer un album MIT - '.config('app.name'),
            'description'=>$description,
            'heading'=>$heading,

        ];
        return view('album.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlbumRequest $request)
    {
      DB::beginTransaction();
        try {
            //albums
           $album = Auth::user()->albums()->create($request->validated());

           //categories
           $categories = explode(',', $request->categories);
           $categories = collect($categories)->filter(function ($value, $key){
               return $value!=='';
           })->all();
        foreach ($categories as $cat){
            $category = Category::firstOrCreate(['name'=>trim($cat)]);
            $album->categories()->attach($category->id);

            //tags
            $tags = explode(',', $request->tags);
            $tags = collect($tags)->filter(function ($value, $key){
                return $value!=='';
            })->all();
            foreach ($tags as $t){
                $tag = Tag::firstOrCreate(['name'=>trim($t)]);
                $album->tags()->attach($tag->id);
            }
        }
        }catch (ValidationException $e){
            DB::rollBack();
            dd($e->getErrors());
        }
      DB::commit();
        $success = 'Album Ajouté';
        $redirect = route('photos.create',[$album->slug]);
        return $request->ajax()
            ? response()->json(['success'=>$success, 'redirect'=>$redirect])
            : redirect($redirect)->withSuccess($success);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(album $album)
    {
        //
    }
}
