<?php

namespace EduLazaro\Larakeep\Tests\Support\Models;

use Illuminate\Database\Eloquent\Model;
use EduLazaro\Larakeep\Concerns\HasKeepers;
use EduLazaro\Larakeep\Attributes\KeptBy;
use EduLazaro\Larakeep\Tests\Support\Keepers\TestAttributeKeeper;

#[KeptBy(TestAttributeKeeper::class)]
class MyAttributeTestModel extends Model
{
    use HasKeepers;

    protected $guarded = [];
}
