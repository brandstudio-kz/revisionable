<?php

namespace BrandStudio\Revisionable\Traits;

trait Identifiable {

    public function identifiableName()
    {
        return trans_choice("admin.{$this->getTable()}", 1)." ".($this->name ?? $this->id);
    }

    public function identifiableLink()
    {
        return backpack_url(strtolower(class_basename(static::class)))."/{$this->id}/show";
    }

}
