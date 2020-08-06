<?php

namespace BrandStudio\Revisionable\Traits;

use BrandStudio\Revisionable\Revision;

trait Revisionable
{
    use \BrandStudio\Revisionable\Traits\Identifiable;
    use \Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;

    protected $revisions_enabled = true;

    public static function bootRevisionable()
    {
        static::created(function($revisionable) {
            Revision::createRevision($revisionable, Revision::CREATED);
        });

        static::deleted(function($revisionable) {
            Revision::createRevision($revisionable, Revision::DELETED);
        });

        static::updating(function($revisionable) {
            if ($revisionable->shouldCreateRevision()) {
                Revision::createRevision($revisionable, Revision::UPDATED);
            }
        });

        static::belongsToManyAttached(function($relation, $parent, $ids, $attributes) {
            if ($ids) {
                Revision::createRevision($parent, Revision::RELATION_CREATED, ['relation' => $relation, 'id' => $ids[0], 'attributes' => $attributes]);
            }
        });

        static::belongsToManyUpdatingExistingPivot(function($relation, $parent, $ids, $attributes) {
            if ($ids) {
                Revision::createRevision($parent, Revision::RELATION_UPDATED, ['relation' => $relation, 'id' => $ids[0], 'attributes' => $attributes]);
            }
        });

        static::belongsToManyDetaching(function($relation, $parent, $ids, $attributes) {
            if ($ids) {
                Revision::createRevision($parent, Revision::RELATION_DELETED, ['relation' => $relation, 'id' => $ids[0], 'attributes' => $attributes]);
            }
        });
    }

    // Relations
    public function revisions()
    {
        return $this->morphMany(Revision::class, 'revisionable');
    }

    // Functions
    public function disableRevisions()
    {
        $this->revisions_enabled = false;
    }

    public function enableRevisions()
    {
        $this->revisions_enabled = true;
    }


    public function shouldHighlight($action) : bool
    {
        return $this->revisions_enabled && (($action != Revision::UPDATED && $action != Revision::RELATION_UPDATED) || $this->isHighlightableFieldDirty());
    }

    public  function shouldCreateRevision() : bool
    {
        return (bool) $this->getDirtyFieldsForRevision();
    }


    public  function isHighlightableFieldDirty() : bool
    {
        if ($this->highlight_revision_on_update ?? false) {
            return (bool) array_intersect($this->getDirtyFieldsForRevision(), $this->highlight_revision_on_update);
        }
        return false;
    }

    public function getDirtyAttributesForRevision() : array
    {
        $dirty = collect($this->getDirty());

        if ($this->revisionable_fields ?? false) {
            $dirty = $dirty->only($this->revisionable_fields);
        }

        if ($rhis->not_revisionable_fields ?? false) {
            $dirty = $dirty->except($this->not_revisionable_fields);
        }

        return $dirty->toArray();
    }

    public function getDirtyFieldsForRevision() : array
    {
        return array_keys($this->getDirtyAttributesForRevision());
    }

    public static function getRevisionableLabels() : array
    {
        return [];
    }

}
