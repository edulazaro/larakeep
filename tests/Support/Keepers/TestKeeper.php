<?php

namespace EduLazaro\Larakeep\Tests\Support\Keepers;

use EduLazaro\Larakeep\Tests\Support\Models\MyTestModel;

class TestKeeper
{
    protected MyTestModel $model;

    public function __construct(MyTestModel $model)
    {
        $this->model = $model;
    }

    public function getSearchText()
    {
        return 'Expected computed value';
    }

    public function getSearchTextWith($string)
    {
        return $string;
    }

    public function configureSearchText()
    {
        return 'Expected configured value';
    }

    public function configureSearchTextWith($string)
    {
        return $string;
    }
}
