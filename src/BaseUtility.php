<?php

namespace MixCode\BaseUtilities;

use Illuminate\Database\Eloquent\Model;
use MixCode\BaseUtilities\Utilities\UsingStatus;
use MixCode\BaseUtilities\Utilities\UsingUuid;

class BaseUtility extends Model
{
    use UsingUuid,
        UsingStatus;

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'disable';

    protected $table = 'base_utilities';
    protected $guarded = [];
}
