<?php

namespace BrandStudio\Revisionable\Traits;

use BrandStudio\Revisionable\Revision;

trait RevisionableTrait
{

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $model->createRevision(null, $model->toArray());
        });

        static::updated(function($model) {
            $model->createRevision($model->getOriginal(), $model->toArray());
        });

        static::deleted(function($model) {
            $model->createRevision($model->toArray());
        });
    }

    public function revisions()
    {
        return $this->hasMany(Revision::class, 'model_id');
    }


    private function createRevision($old = null, $new = null)
    {
        $revision = new Revision([
            'responsible_id' => config('revisionable.getResponsibleId')(),
            'model' => static::class,
            'model_id' => $new['id'] ?? $old['id'],
            'action' => $old ? ($new ? Revision::UPDATED : Revision::DELETED) : Revision::CREATED,
        ]);

        $revision->old = [
            'original' => $old,
            'pretty' => static::prepareValue($old)
        ];

        $revision->new = [
            'original' => $new,
            'pretty' => static::prepareValue($new)
        ];
        $revision->save();
    }

    protected static function prepareValue($val)
    {
        if (!$val) {
            return null;
        }

        $model = new static($val);
        $res = [];
        foreach($val as $key => $value) {
            if (!in_array($key, ['created_at', 'updated_at'])) {
                $res[$key] = $model->{$key};
            }
        }

        return $res;
    }

}
