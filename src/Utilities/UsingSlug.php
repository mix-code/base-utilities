<?php

namespace MixCode\BaseUtilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait UsingSlug
{
    protected static function bootUsingSlug()
    {
        static::creating(function(Model $model) {
            $model->{$model->getSlugKey()} = $model->{$model->getRawKey()};
        });
    }

    /**
     * Get the slug field key.
     *
     * @return string
     */
    public function getSlugKey()
    {
        return 'slug';
    }

    /**
     * Get the Raw field key.
     * this key is your normal key like (name, title, en_name, en_title)
     *
     * @return string
     */
    public function getRawKey()
    {
        return 'ar_title';
    }

    /**
     * Define Slug Field Mutator
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value, '-', 'ar');
        
        $isSlugExists = static::where('slug', $slug)->exists();
        
        if ($isSlugExists) {
            $id = Arr::first(explode('-', $this->attributes['id']));
            $slug = "$slug-{$id}";    
        }

        $this->attributes['slug'] = $slug;
    }
}