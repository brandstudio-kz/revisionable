<?php

namespace BrandStudio\Revisionable\Traits;

use BrandStudio\Revisionable\Revision;

trait Responsible
{
    use \BrandStudio\Revisionable\Traits\Identifiable;

    public function revisions()
    {
        return $this->morphMany(Revision::class, 'responsible');
    }

    public function identifiableName()
    {
        return $this->name ?? $this->id;
    }

}
