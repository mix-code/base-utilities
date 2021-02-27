<?php

namespace MixCode\BaseUtilities;

use Illuminate\Database\Eloquent\Model;

trait RefreshCache
{
    protected static function bootRefreshCache()
    {
        static::saved(function (Model $model) {
            cache()->forget($model->cacheKey());
            
        });

        static::deleted(function (Model $model) {
            cache()->forget($model->cacheKey());
        });
    }

    protected function cacheKey()
    {
        return $this->getTable();
    }
}
