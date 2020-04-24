<?php

namespace Statch\Statuses\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface Status
{
    public function statusable(string $class): MorphTo;
}
