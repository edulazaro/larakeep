<?php

namespace EduLazaro\Larakeep\Tests\Support\Keepers;

use EduLazaro\Larakeep\Tests\Support\Models\MyAttributeTestModel;

class TestAttributeKeeper
{
    protected MyAttributeTestModel $model;

    public function __construct(MyAttributeTestModel $model)
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
