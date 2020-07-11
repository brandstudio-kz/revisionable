<?php

namespace BrandStudio\Revisionable\Traits;

use BrandStudio\Revisionable\Revision;

trait RevisionableTrait
{

    public static function bootRevisionableTrait()
    {
        static::created(function($model) {
            $model->createRevision(null, $model->toArray());
        });

        static::updated(function($model) {
            if ($model->shouldCreateRevision()) {
                $model->createRevision($model->getOriginal(), $model->toArray());
            }
        });

        static::deleted(function($model) {
            if ($model->shouldCreateRevision()) {
                $model->createRevision($model->toArray());
            }
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

        $revision->old = $old ? [
            'original' => $old,
            'pretty' => static::prepareValues($old)
        ] : null;

        $revision->new = $new ? [
            'original' => $new,
            'pretty' => static::prepareValues($new)
        ] : $new;

        $revision->save();
    }

    protected static function prepareValues($val)
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

    protected function shouldCreateRevision() : bool
    {
        return true;
    }

}
