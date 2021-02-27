<?php

namespace MixCode\BaseUtilities\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MixCode\BaseUtilities\BaseUtilitiesServiceProvider;
use MixCode\BaseUtilities\BaseUtility;
use Orchestra\Testbench\TestCase;

class StatusUtilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Base Utility Instance
     *
     * @var \MixCode\BaseUtilities\BaseUtility
     */
    protected $model;

    protected function getPackageProviders($app)
    {
        return [BaseUtilitiesServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = BaseUtility::create();
    }

    /** @test */
    public function it_can_determine_if_the_it_is_active_or_disable()
    {
        $active     = BaseUtility::create(['status' => BaseUtility::ACTIVE_STATUS]);
        $disable    = BaseUtility::create(['status' => BaseUtility::INACTIVE_STATUS]);

        // Active Unit Tests
        $this->assertTrue($active->hasStatus(BaseUtility::ACTIVE_STATUS));
        $this->assertTrue($active->isActive());
        $this->assertFalse($active->isInActive());

        // InActive Unit Tests
        $this->assertTrue($disable->hasStatus(BaseUtility::INACTIVE_STATUS));
        $this->assertTrue($disable->isInActive());
        $this->assertFalse($disable->isActive());
    }

    /** @test */
    public function it_can_mark_as_active()
    {
        $this->model->markAsActive();

        $this->assertTrue($this->model->isActive());
    }

    /** @test */
    public function it_can_mark_as_in_active()
    {
        $this->model->markAsInActive();

        $this->assertTrue($this->model->isInActive());
    }
}
