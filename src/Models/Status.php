<?php

namespace Stacht\Statuses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stacht\Statuses\Contracts\Status as StatusContract;


class Status extends Model implements StatusContract
{

    protected $fillable = [
        'slug',
        'name',
        'hex_color',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('stacht-statuses.tables.statuses'));
    }

    public static function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'hex_color' => 'required|string|max:100',
            'slug' => 'required|alpha_dash|max:150|unique:'.config('stacht-statuses.tables.statuses').',slug'
        ];
    }

    /**
     * Get all attached models of the given class to the category.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function statusable(string $class): MorphTo
    {
        return $this->morphTo($class, 'statusable', config('stacht-statuses.tables.statusables'), 'status_id', 'statusable_id');
    }

}
