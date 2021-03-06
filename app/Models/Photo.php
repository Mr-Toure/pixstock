<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Photo extends Model
{
    use HasFactory, HasSlug;
    protected $fillable = ['title', 'album_id'];
    protected $perPage = 6;

    protected static function booted()
    {
        static::addGlobalScope('active', function(Builder $builder){
            $builder->where('active', true);
        });
    }

    public function getRouteKeyName()
    {
        return'slug';
    }
    /**
     * @return SlugOptions
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * .
     */
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    /**
     * .
     */
    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }
}
