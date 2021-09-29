<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Photo extends Model
{
    use HasFactory, HasSlug;

    protected $perPage = 6;

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
}
