<?php

namespace EduLazaro\Larakeep\Tests\Support\Models;

use Illuminate\Database\Eloquent\Model;
use EduLazaro\Larakeep\Concerns\HasKeepers;

class MyTestModel extends Model
{
    use HasKeepers;

    protected $guarded = [];
}