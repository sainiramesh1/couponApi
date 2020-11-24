<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Str;

class CouponTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCouponTest()
    {
        $coupon = Str::random(6);
        $response = $this->post('api/coupon-generate', ['code' => $coupon,'amount'=>100,'lat'=>28.3948003,'lng'=>76.97386709999999,'radius_km'=>5]);

       $response
           ->assertStatus(200)
           ->assertJson([
               'success' => 1,
           ]);
        $response = $this->post('api/coupon-generate', ['code' => $coupon,'amount'=>100]);
   
          $response
              ->assertStatus(200)
              ->assertJson([
                  'success' => -1,
              ]);
        
                
    }

    public function testCoupon1Test()
    {
        $coupon = Str::random(6);
        $response = $this->post('api/coupon-generate', ['code' => $coupon,'amount'=>100,'lat'=>28.3948003,'lng'=>76.97386709999999,'radius_km'=>5]);

       $response
           ->assertStatus(200)
           ->assertJson([
               'success' => 1,
           ]);
        $response = $this->post('api/coupon-generate', ['code' => $coupon,'amount'=>100]);
   
          $response
              ->assertStatus(200)
              ->assertJson([
                  'success' => -1,
              ]);
        $response = $this->post('api/check-coupon-validity', ['code' => $coupon,'lat_from'=>28.3956097,'lng_from'=>76.979624,'lat_to'=>28.435859,'lng_to'=>77.0495017]);
   
              $response
                  ->assertStatus(200)
                  ->assertJson([
                      'success' => 1,
                  ]);
        
                
    }


    public function testCoupon2Test()
    {
        $coupon = Str::random(6);
        $response = $this->post('api/coupon-generate', ['code' => $coupon,'amount'=>100,'lat'=>28.4141695,'lng'=>77.0635405,'radius_km'=>5]);

       $response
           ->assertStatus(200)
           ->assertJson([
               'success' => 1,
           ]);
        $response = $this->post('api/coupon-generate', ['code' => $coupon,'amount'=>100]);
   
          $response
              ->assertStatus(200)
              ->assertJson([
                  'success' => -1,
              ]);
        $response = $this->post('api/check-coupon-validity', ['code' => $coupon,'lat_from'=>28.3956097,'lng_from'=>76.979624,'lat_to'=>28.5002808,'lng_to'=>77.0790804]);
   
              $response
                  ->assertStatus(200)
                  ->assertJson([
                      'success' => -1,
                  ]);
        
                
    }




    public function testCouponlistTest(){
        $response = $this->get('api/get-coupons', ['only_active'=>1]);

       $response
           ->assertStatus(200)
           ->assertJson([
               'success' => 1,
           ]);
    }
}
