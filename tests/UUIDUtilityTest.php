<?php

namespace MixCode\BaseUtilities\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\BaseUtilities\BaseUtilitiesServiceProvider;
use MixCode\BaseUtilities\BaseUtility;
use Orchestra\Testbench\TestCase;

class UUIDUtilityTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [BaseUtilitiesServiceProvider::class];
    }

    /** @test */
    public function it_can_create_a_uuid_as_model_id()
    {
        $model = BaseUtility::create();
        
        $this->assertTrue(is_string($model->id));
        $this->assertRegExp('/[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(a|b|8|9)[a-f0-9]{3}\-[a-f0-9]{12}/', $model->id);
    }
}