<?php

namespace EduLazaro\Larakeep\Tests\Feature;

use EduLazaro\Larakeep\Tests\TestCase;
use EduLazaro\Larakeep\Tests\Support\Models\MyTestModel;
use EduLazaro\Larakeep\Tests\Support\Keepers\TestKeeper;
use EduLazaro\Larakeep\Tests\Support\Models\MyAttributeTestModel;

class KeeperTest extends TestCase
{
    public function test_it_assigns_keepers_manually()
    {
        MyTestModel::keep(TestKeeper::class);
        $this->assertNull(MyTestModel::bootKeepers());

        $model = new MyTestModel();
        $model->process('search_text');

        $this->assertEquals('Expected computed value', $model->search_text);
    }

    public function test_it_assigns_keepers_via_attributes()
    {
       $this->assertNull(MyAttributeTestModel::bootKeepers());

       $model = new MyAttributeTestModel();
       $model->process('search_text');

       $this->assertEquals('Expected computed value', $model->search_text);
    }

    public function test_it_processes_task_fields_correctly()
    {
        $model = new MyTestModel();
        $model->process('search_text');
        $model->processTask('configure','search_text');
        $this->assertEquals('Expected configured value', $model->search_text);
    }

    public function test_it_processes_fields_correctly_with_a_parameter()
    {
        $model = new MyTestModel();
        $model->processWith('search_text', ['string' => 'Hey']);

        $this->assertEquals('Hey', $model->search_text);
    }

    public function test_it_processes_task_fields_correctly_with_a_parameter()
    {
        $model = new MyTestModel();
        $model->process('search_text');
        $model->processTaskWith('configure','search_text', ['string' => 'Hey']);
        $this->assertEquals('Hey', $model->search_text);
    }
}
