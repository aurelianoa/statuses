<?php

namespace Statch\Statuses\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Statch\Status\Contracts\Status;
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
    public function status(): MorphToMany
    {
        return $this->morphToMany(config('statch-statuses.models.status'), 'statusable', config('statch-statuses.tables.statusables'), 'statusable_id', 'status_id')
                    ->withTimestamps();
    }

    /**
     * Boot the categorizable trait for the model.
     */
    public static function bootStatusable()
    {
        static::deleted(function (self $model) {
            // The model was just soft deleted?
            if (!$model->exists) {
                $model->statuses()->detach();
            }
        });
    }

    /**
     * Determine if the model has any of the given categories.
     *
     * @param mixed $status
     *
     * @return bool
     */
    public function hasStatus($status): bool
    {
        return !$this->status()
            ->where('name',$status)
            ->orWhere('slug',$status)
            ->get()
            ->isEmpty();
    }


    /**
     * Sync model status.
     *
     * @param mixed $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $syncIds = [];

        if (is_numeric($status)) {
            $syncIds[] = $status;
        } else if (is_string($status)) {
            $status = app(config('statch-statuses.models.status'))->firstWhere(['slug' => $status]);

            if (! empty($status)) {
                $syncIds[] = $status->getKey();
            }
        } else if ($status instanceof Model) {
            $syncIds[] = $status->getKey();
        }

        // Sync model ids
        $this->status()->sync($syncIds);

        return $this;
    }

}
