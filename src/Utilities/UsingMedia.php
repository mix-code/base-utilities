<?php

namespace MixCode\BaseUtilities;

use Spatie\MediaLibrary\Models\Media;

trait UsingMedia
{
    public function registerMediaCollection()
    {
        $this
            ->addMediaCollection($this->mainMediaKey());
            // ->registerMediaConversions(function (Media $media) {
            //     $this
            //         ->addMediaConversion('thumb')
            //         ->width(100)
            //         ->height(100)
            //         ->nonQueued();
            // });
    }

    /**
     * Upload Multiple Media From Request
     *
     * @param array $media
     * @param string||null $postfix_key
     * @param string $postfix_media_type
     * @param boolean $preserving_original
     * @return void
     */
    public function uploadMultiMediaFromRequest($media, $postfix_key = null, $postfix_media_type = 'image', $preserving_original = false)
    {
        foreach ($media as $mediaName) {
            if (\File::isFile($mediaName)) {
        		$filename = md5($mediaName->getClientOriginalName()) . '.' . $mediaName->getClientOriginalExtension();


                $uploadedMedia = $this->addMedia($mediaName);
                
                $uploadedMedia->setName($filename)->setFileName($filename);
                
                if ($preserving_original) {
                    $uploadedMedia->preservingOriginal();    
                }

                $uploadedMedia->toMediaCollection($this->mainMediaKey($postfix_key, $postfix_media_type));
            }
        }

        return $this;
    }

    /**
     * Upload Media From The Request
     *
     * @param string $mediaName
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return void
     */
    public function uploadSingleMediaFromRequest($mediaName, $postfix_key = null, $postfix_media_type = 'image')
    {
        $file = request()->file($mediaName);
        $filename = md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

        return $this->addMediaFromRequest($mediaName)->setName($filename)->setFileName($filename)->toMediaCollection($this->mainMediaKey($postfix_key, $postfix_media_type));
    }

    /**
     * Upload Single Media
     *
     * @param File $media
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return void
     */
    public function uploadSingleMedia($media, $postfix_key = null, $postfix_media_type = 'image')
    {
        $file = $media;
        $filename = md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

        return $this->addMedia($media)->setName($filename)->setFileName($filename)->toMediaCollection($this->mainMediaKey($postfix_key, $postfix_media_type));
    }

    /**
     * Update Media
     * After Removing The Old One
     * Useful with single media
     *
     * @param File $media
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return void
     */
    public function updateSingleMedia($media, $postfix_key = null, $postfix_media_type = 'image')
    {
        $this->clearMediaCollection($this->mainMediaKey($postfix_key, $postfix_media_type));
        
        return $this->uploadSingleMedia($media, $postfix_key, $postfix_media_type);
    }

    /**
     * Update Media From The Request
     * After Removing The Old One
     * Useful with single media
     *
     * @param string $mediaName
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return void
     */
    public function updateSingleMediaFromRequest($mediaName, $postfix_key = null, $postfix_media_type = 'image')
    {
        $this->clearMediaCollection($this->mainMediaKey($postfix_key, $postfix_media_type));
        
        return $this->uploadSingleMediaFromRequest($mediaName, $postfix_key, $postfix_media_type);
    }

    public function updateWithMedia($data, $request, $media_name = 'image')
    {
        $this->update($data);

        if ($request->hasFile($media_name)) {
            $this->updateSingleMediaFromRequest($media_name);
        }
        
        return $this;
    }

    /**
     * Copy Media
     *
     * @param \Spatie\MediaLibrary\Models\Media $media
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return Collection
     */
    public function duplicateMedia($media, $postfix_key = null, $postfix_media_type = 'image')
    {
        return $media->copy($this, $this->mainMediaKey($postfix_key, $postfix_media_type));
    }
    
    /**
     * Get All Media
     *
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return Collection
     */
    public function allMedia($postfix_key = null, $postfix_media_type = 'image')
    {
        return $this->getMedia($this->mainMediaKey($postfix_key, $postfix_media_type));
    }

    /**
     * Get All Media Paths
     *
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return Collection
     */
    public function allMediaPaths($postfix_key = null, $postfix_media_type = 'image')
    {
        $media = $this->getMedia($this->mainMediaKey($postfix_key, $postfix_media_type));

        return $media->map(function ($media) {
            return $media->getPath();
        });
    }
    
    /**
     * Get Main / first Media
     *
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return Collection
     */
    public function mainMedia($postfix_key = null, $postfix_media_type = 'image')
    {
        return $this->getFirstMedia($this->mainMediaKey($postfix_key, $postfix_media_type)) ?? $this->defaultMediaUrl();
    }
    
    /**
     * Get Main / first Media Url
     *
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return string
     */
    public function mainMediaUrl($postfix_key = null, $postfix_media_type = 'image')
    {
        // Get Media Path
        $filePath = $this->getFirstMediaPath($this->mainMediaKey($postfix_key, $postfix_media_type));

        // Get Media Url
        $url = $this->getFirstMediaUrl($this->mainMediaKey($postfix_key, $postfix_media_type));
        
        // If Medial Url is Not Null And Exists In Storage return Media Url
        if (!! $url && \File::exists($filePath)) return $url;
        
        // Return Default Image
        return $this->defaultMediaUrl();
    }
    
    /**
     * Get Main / first Media Url By Lang
     *
     * @param string $postfix_key give the postfix key with out any additional en|ar characters EX: 'en_image' be 'image'
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return string
     */
    public function mainMediaUrlByLang($postfix_key = 'image', $postfix_media_type = 'image')
    {
        $postfix_key = app()->getLocale() . "_{$postfix_key}";

        // Get Media Path
        $filePath = $this->getFirstMediaPath($this->mainMediaKey($postfix_key, $postfix_media_type));

        // Get Media Url
        $url = $this->getFirstMediaUrl($this->mainMediaKey($postfix_key, $postfix_media_type));
        
        // If Medial Url is Not Null And Exists In Storage return Media Url
        if (!! $url && \File::exists($filePath)) return $url;
        
        // Return Default Image
        return $this->defaultMediaUrl();
    }
    
    /**
     * Pin Media by swapping media order_columns value with the first media
     *
     * @param int $media_id
     * @return $this
     */
    public function pinMedia($media_id)
    {
        $selectedMedia  = $this->media()->find($media_id);
        $mainMedia      = $this->mainMedia();
        
        $mainOrder      = $mainMedia->order_column;
        $swapOrder      = $selectedMedia->order_column;
        
        $mainMedia->update(['order_column' => $swapOrder]);
        
        $selectedMedia->update(['order_column' => $mainOrder]);

        return $this;
    }

    
    /**
     * Get Main / first Media ID
     *
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return string
     */
    public function mainMediaId($postfix_key = null, $postfix_media_type = 'image')
    {
        // Get Media Path
        $filePath = $this->getFirstMediaPath($this->mainMediaKey($postfix_key, $postfix_media_type));

        // Get First Media
        $firstMedia = $this->getFirstMedia($this->mainMediaKey($postfix_key, $postfix_media_type));
        
        // If Medial Exists is Not Null And Exists In Storage return Media Url
        if (!! $firstMedia && \File::exists($filePath))  return $firstMedia->id;

        // Return Default Image
        return $this->defaultMediaUrl();
    }

    public function getMainMediaUrlAttribute()
    {
        return $this->safeMediaUrl($this->mainMediaUrl());
    }

    
    /**
     * Check Whether media exists or not 
     * if exists return "$media"
     * if not exists return null
     *
     * @param mixed $media
     * @return mixed
     */
    public function safeMediaUrl($media)
    {
        if (strpos($media, 'placehold.co') === false && strpos($media, 'avatar.png') === false) {
            return $media;
        }

        return null;
    }

    /**
     * Get Main Media key
     * Useful When working with different media for the same Model
     *
     * @param string $postfix_key prefix key for media EX: model_image be mode_image_{$postfix_key}
     * @param string $postfix_media_type key for media type EX: mode_{$postfix_media_type}_{$postfix_key}
     * @return string
     */
    public function mainMediaKey($postfix_key = null, $postfix_media_type = 'image')
    {
        $model = strtolower((new \ReflectionClass($this))->getShortName());
        $key = "{$model}_{$postfix_media_type}";
        if (!! $postfix_key) {
            $key = "{$key}_{$postfix_key}";
        }

        return $key;
    }

    protected function defaultMediaUrl()
    {
        return 'https://placehold.co/600x400/5a5c69/fff?text=No%20Image';
    }
}
