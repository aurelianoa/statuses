<?php

namespace Stacht\Statuses\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// use Stacht\Status\Contracts\Status;
use \Stacht\Statuses\Models\Status;
use Illuminate\Support\Arr;


trait Statusable
{
    /**
     * Register a saved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    abstract public static function saved($callback);

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    abstract public static function deleted($callback);


    /**
     * Get all attached categories to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function statuses(): MorphToMany
    {
        return $this->morphToMany(
            config('stacht-statuses.models.status'),
            'statusable',
            config('stacht-statuses.tables.statusables'),
            'statusable_id',
            'status_id'
        )
            ->latest('id')
            ->withTimestamps();
    }

    public function latestStatus(): ?Status
    {
        $statuses = $this->relationLoaded('statuses') ? $this->statuses : $this->statuses();

        return $statuses->first();
    }

    public function status(): ?Status
    {
        return $this->latestStatus();
    }

    public function getStatusAttribute()
    {
        return $this->latestStatus();
    }



    public static function bootStatusable()
    {
        static::deleted(function (self $model) {
            // The model was just soft deleted?
            if (!$model->exists) {
                $model->statuses()->detach();
            }
        });
    }


    public function hasStatus($slug): bool
    {
        return optional($this->status)->slug === $slug;
    }

    public function setStatus($status)
    {
        $syncIds = [];

        if (is_numeric($status)) {
            $syncIds[] = $status;
        } else if (is_string($status)) {
            $status = app(config('stacht-statuses.models.status'))->firstWhere(['slug' => $status]);

            if (!empty($status)) {
                $syncIds[] = $status->getKey();
            }
        } else if ($status instanceof Model) {
            $syncIds[] = $status->getKey();
        }

        // Sync model ids
        $this->statuses()->sync($syncIds);

        return $this;
    }

    public function scopeCurrentStatus(Builder $builder, ...$slugs)
    {
        $slugs = is_array($slugs) ? Arr::flatten($slugs) : func_get_args();

        $builder
            ->whereHas('statuses', function (Builder $query) use ($slugs) {
                $query->whereIn('slug', $slugs);
            });
    }

    public function scopeOtherCurrentStatus(Builder $builder, ...$slugs)
    {
        $slugs = is_array($slugs) ? Arr::flatten($slugs) : func_get_args();

        $builder
            ->whereHas('statuses', function (Builder $query) use ($slugs) {
                $query->whereNotIn('slug', $slugs);
            });
    }
}
