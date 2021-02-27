<?php

namespace MixCode\BaseUtilities;

use Illuminate\Database\Eloquent\Model;
use MixCode\BaseUtilities\Utilities\UsingUuid;

class BaseUtility extends Model
{
    use UsingUuid;

    protected $table = 'base_utilities';
    protected $guarded = [];
}
