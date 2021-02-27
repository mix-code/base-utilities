<?php

namespace MixCode\BaseUtilities\Utilities;

use Illuminate\Database\Eloquent\Builder;

trait UsingStatus
{
    public function hasStatus($status)
    {
        return $this->status == $status;
    }

    public function isActive()
    {
        return $this->hasStatus(static::ACTIVE_STATUS);
    }

    public function isInActive()
    {
        return $this->hasStatus(static::INACTIVE_STATUS);
    }

    public function scopeActive(Builder $q)
    {
        return $q->where('status', static::ACTIVE_STATUS);
    }
    
    public function scopeInActive(Builder $q)
    {
        return $q->where('status', static::INACTIVE_STATUS);
    }

    public function markAsActive()
    {
        $this->update(['status' => static::ACTIVE_STATUS]);

        return $this;
    }
    
    public function markAsInActive()
    {
        $this->update(['status' => static::INACTIVE_STATUS]);

        return $this;
    }
    
}