<?php

namespace BrandStudio\Revisionable\Traits;

use BrandStudio\Revisionable\Models\Revision;

trait Revisionable
{

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {

        });

        static::updated(function($model) {

        });

        static::deleted(function($model) {

        });
    }

}
