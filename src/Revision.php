<?php

namespace BrandStudio\Revisionable;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{

    const CREATED = 0;
    const UPDATED = 1;
    const DELETED = 2;

    protected $table = 'revisions';
    protected $guarded = ['id'];

    protected $fillable = [
        'model', 'model_id',
        'action', 'responsible_id',
        'old', 'new',
    ];

    protected $casts = [
        'old' => 'array',
        'new' => 'array'
    ];

    public function getActionOptions() : array
    {
        return [
            static::CREATED => trans('revisionable.created'),
            static::UPDATED => trans('revisionable.updated'),
            static::DELETED => trans('revisionable.deleted'),
        ];
    }

    public function getEnity()
    {
        return $model::find($this->model_id);
    }

}
