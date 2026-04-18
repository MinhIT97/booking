<?php

namespace Modules\Admin\Filters\User;

use App\Filters\BasePipeline;
use Modules\Admin\Filters\User\Filters\RoleFilter;
use Modules\Admin\Filters\User\Filters\StatusFilter;
use Modules\Admin\Filters\User\Filters\UserSearchFilter;

class UserFilterPipeline extends BasePipeline
{
    protected array $pipes = [
        RoleFilter::class,
        StatusFilter::class,
        UserSearchFilter::class,
    ];
}
