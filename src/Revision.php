<?php

namespace BrandStudio\Revisionable;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{

    const CREATED = 0;
    const UPDATED = 1;
    const DELETED = 2;
    const RELATION_CREATED = 3;
    const RELATION_UPDATED = 4;
    const RELATION_DELETED = 5;

    protected $table = 'revisions';
    protected $guarded = ['id'];
    public $timestamps = ['created_at'];

    protected $fillable = [
        'revisionable_description', 'revisionable_type', 'revisionable_id',
        'responsible_description', 'responsible_type', 'responsible_id',
        'action',
        'old', 'new', 'highlight'
    ];

    public $casts = [
        'highlight' => 'boolean',
        'old' => 'array',
        'new' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($revision) {
            $responsible = config('revisionable.getResponsible')();
            $revision->responsible_description = $responsible ? ($responsible->identifiableName() ?? $responsible->getKey()) : 'Система';
            $revision->responsible_type = config('revisionable.responsible_type');
            $revision->responsible_id = $responsible ? $responsible->getKey() : null;
        });
    }

    // Functions
    public static function createRevision($revisionable, $action, $data = null)
    {
        $old = static::hasOld($action) ? static::getOld($revisionable, $action, $data) : null;
        $new = static::hasNew($action) ? static::getNew($revisionable, $action, $data) : null;

        static::createEloquentRevision($revisionable, $action, $old, $new);
    }

    protected static function createEloquentRevision($revisionable, $action, $old, $new)
    {
        $class = get_class($revisionable);

        static::create([
            'revisionable_description'  => (new $class($revisionable->getAttributes()))->identifiableName() ?? $revisionable->getKey(),
            'revisionable_type'         => $class,
            'revisionable_id'           => $revisionable->getKey(),
            'action'                    => $action,
            'highlight'                 => $revisionable->shouldHighlight($action),
            'old'                       => $old,
            'new'                       => $new,
        ]);
    }

    public static function getOld($revisionable, $action, $data = null)
    {
        if (static::isRelation($action)) {
            $relation = $revisionable->{$data['relation']}();
            $related = $relation->find($data['id']);

            $data['description'] = method_exists($related, 'identifiableName') ? ($related->identifiableName() ?? $related->getKey()) : $related->getKey();

            $pivot_columns = $relation->getPivotColumns();
            $data['attributes'] = $relation->find($data['id'])->pivot->only($pivot_columns) ?? [];

            return $data;
        }

        return collect($revisionable->getRawOriginal())->only($revisionable->getDirtyFieldsForRevision())->toArray();
    }

    public static function getNew($revisionable, $action, $data = null)
    {
        if (static::isRelation($action)) {
            $relation = $revisionable->{$data['relation']}();
            $related = $relation->find($data['id']);
            $data['description'] = method_exists($related, 'identifiableName') ? ($related->identifiableName() ?? $related->getKey()) : $related->getKey();

            return $data;
        }

        return $revisionable->getDirtyAttributesForRevision();
    }

    public static function hasOld($action) : bool
    {
        return $action != static::CREATED && $action != static::RELATION_CREATED;
    }

    public static function hasNew($action) : bool
    {
        return $action != static::DELETED && $action != static::RELATION_DELETED;
    }

    public static function isRelation($action) : bool
    {
        return $action == static::RELATION_CREATED || $action == static::RELATION_UPDATED || $action == static::RELATION_DELETED;
    }

    // Relations
    public function revisionable()
    {
        return $this->morphTo();
    }

    public function responsible()
    {
        return $this->morphTo();
    }

    // Accessors
    public function getActionNameAttribute() : string
    {
        return static::getActionName($this->action);
    }

    public static function getActionName($action) : string
    {
        switch ($action) {
            case static::CREATED:
                return 'created';
            case static::DELETED:
                return 'deleted';
            case static::RELATION_CREATED:
                return 'relation_created';
            case static::RELATION_UPDATED:
                return 'relation_updated';
            case static::RELATION_DELETED:
                return 'relation_deleted';
            default:
                return 'updated';
        }
    }

    public function getFieldsCnt() : int
    {
        return count($this->getFields());
    }

    public function getFields() : array
    {
        return array_keys($this->new ?? []);
    }

    public function getLabels() : array
    {
        $labels = [];

        foreach($this->getFields() as $field) {
            $labels[] = $this->getFieldLabel($field);
        }

        return $labels;
    }

    public function getFieldLabel(string $field, $singular = false) : string
    {
        $revisionable_labels = $this->revisionable_type::getRevisionableLabels();
        if (isset($revisionable_labels[$field])) {
            return $revisionable_labels[$field];
        }

        if (!$singular) {
            $description = static::getFieldLabel($field, true);
            if (\Str::contains($description, '|')) {
                return explode('|', $description)[1];
            }
            return $description;
        }

        $plural = \Str::plural($field);

        if (\Lang::has("revisionable::revision.{$field}")) {
            return trans("revisionable::revision.{$field}");
        }

        if (\Lang::has("revisionable::revision.{$plural}")) {
            return trans("revisionable::revision.{$plural}");
        }

        if (\Lang::has("admin.{$field}")) {
            return trans("admin.{$field}");
        }

        if (\Lang::has("admin.{$plural}")) {
            return trans("admin.{$plural}");
        }

        return $field;
    }

}
