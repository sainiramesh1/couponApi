<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Coupon;
use Log;

class CouponController extends Controller
{
    function generate_coupon(Request $request){
        // Validation 

        try{
            $coupon = new Coupon;
            $coupon = $coupon->where('code',$request->code)->first();
            if($coupon)
                return response()->json(['success'=>-1,'msg'=>'Coupon code already exist']);

            $coupon = new Coupon;
            $coupon->code = $request->code;
            $coupon->amount = $request->amount;
            if($coupon->expiry_date!='')
                $coupon->expiry_date = date('Y-m-d H:i:s',strtotime($request->expiry_date));
            if($request->lat!='' && $request->lng!='' && $request->radius_km>0){
                $coupon->lat = $request->lat;
                $coupon->lng = $request->lng;
                $coupon->radius_km = $request->radius_km;
            }
            
            $coupon->save();
        }catch(\Exception $e){
            return response()->json(['success'=>-1,'msg'=>'Something went wrong']);
        }
        return response()->json(['success'=>1,'msg'=>'Coupon Created Succefully']);

    }

    function get_coupons(Request $request, Coupon $coupon){
        if($request->only_active==1)
            $coupon->where('status',1);
        $coupons = $coupon->get();
        return response()->json(['success'=>1,'msg'=>'Coupons','data'=>$coupons]);
    }

    function check_validity(Request $request, Coupon $coupon){
        $coupon = $coupon->where('code',$request->code)->first();
        Log::info('Looking for Coupon : '.$request->code);
        if($coupon){
            Log::info('Coupon : '.$request->code." Found");    
            if($coupon->lat!='' && $coupon->lng!='' && $coupon->radius_km>0){
                $orig_distance = $this->haversineGreatCircleDistance(
                    $coupon->lat, $coupon->lng, $request->lat_from, $request->lng_from);
                Log::info("Origin Distance  From event ->".$orig_distance);
                $dest_distance = $this->haversineGreatCircleDistance(
                        $coupon->lat, $coupon->lng, $request->lat_to, $request->lng_to);
                Log::info("Destination Distance  From event ->".$dest_distance);
                if($coupon->radius_km*1000<$dest_distance && $coupon->radius_km*1000<$orig_distance)    
                        return response()->json(['success'=>-1,'msg'=>'Invalid or Expired Coupon']);
                    
            }
            return response()->json(['success'=>1,'msg'=>'Valid Coupon','data'=>$coupon]);
        }
        Log::info('Coupon : '.$request->code." Not Found");    
        return response()->json(['success'=>-1,'msg'=>'Invalid Coupon Or Expired Coupon']);
    }

        //

    /**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function haversineGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
  {
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);
  
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;
  
    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
  }


}
