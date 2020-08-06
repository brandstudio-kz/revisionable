<?php

namespace BrandStudio\Revisionable\Traits;

trait Identifiable {

    public function identifiableName()
    {
        return $this->name ?? $this->id;
    }

    public function identifiableLink()
    {
        return backpack_url(strtolower(class_basename(static::class)))."/{$this->id}/show";
    }

}
