<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestCouponApi extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->post('/coupon-generate', ['code' => 'ABC60','amount'=>100])
             ->seeJson([
                 'created' => true,
             ]);
    }
}
