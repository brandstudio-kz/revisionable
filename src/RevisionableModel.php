<?php

namespace BrandStudio\Revisionable;

use Illuminate\Database\Eloquent\Model;

use BrandStudio\Revisionable\Traits\RevisionableTrait;

class RevisionableModel extends Model
{
    use RevisionableTrait;


}
