<?php

namespace MixCode\BaseUtilities;

use Illuminate\Database\Eloquent\Builder;

trait UsingPlatformStatus
{
    public function hasPlatform($platform)
    {
        return $this->platform == $platform;
    }

    public function hasWebPlatform()
    {
        return $this->hasPlatform(static::WEB_PLATFORM);
    }

    public function hasAndroidPlatform()
    {
        return $this->hasPlatform(static::ANDROID_PLATFORM);
    }

    public function hasIosPlatform()
    {
        return $this->hasPlatform(static::IOS_PLATFORM);
    }

    public function scopeWebPlatform(Builder $q)
    {
        return $q->where('platform', static::WEB_PLATFORM);
    }
    
    public function scopeAndroidPlatform(Builder $q)
    {
        return $q->where('platform', static::ANDROID_PLATFORM);
    }
    
    public function scopeIosPlatform(Builder $q)
    {
        return $q->where('platform', static::IOS_PLATFORM);
    }

    public function paidOn($platform)
    {
        $this->platform = $platform;

        $this->save();

        return $this;
    }
    
    public function paidOnWebPlatform()
    {
        return $this->paidOn(static::WEB_PLATFORM);
    }
    
    public function paidOnAndroidPlatform()
    {
        return $this->paidOn(static::ANDROID_PLATFORM);
    }
    
    public function paidOnIosPlatform()
    {
        return $this->paidOn(static::IOS_PLATFORM);
    }
}