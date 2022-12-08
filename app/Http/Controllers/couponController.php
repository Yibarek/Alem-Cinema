<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\validator;

class couponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(){
        $coupons = DB::table('coupons')->orderBy('id')->get();
        $users = DB::table('users')->orderBy('username')->get();
        return view('coupons.coupons', ['coupons'=>$coupons, 'users'=>$users]);
    }

    public function createCoupon(Request $request)
    {
        if(Auth::user()->role == 'Admin'){
            $return_code=0;
            $return_code = DB::table('coupons')->insertOrIgnore([
                'user'=>$request->user,
                'coupon'=>$request->coupon,
            ]);
            if ($return_code == 1) {
                $previous = DB::table('users')->where('username', $request->user)->first();
                $new = $previous->account + $request->coupon;
                $now = DB::table('users')->where('username', $request->user)->update(['account' => $new]);

                return redirect('/coupon')->with('success', "User $request->user successfully bought $request->coupon ammount coupon!");
            }
            else{
                return redirect('/coupon')->with('danger', 'Unable to bought the coupon now! Try again later please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    public function updateCoupon(Request $request, $coupon_id)
    {
        if(Auth::user()->role == 'Admin'){
            $coupon = DB::table('coupons')->where('id', $coupon_id)->first();
            $return_code=0;
            $return_code = DB::table('coupons')->where('id', $coupon_id)->update([
                'user'=>$request->user,
                'coupon'=>$request->coupon,
            ]);
            if ($return_code == 1) {
                $previous = DB::table('users')->where('username', $request->user)->first();
                if ($request->coupon >= $coupon->coupon) {
                    $new = $previous->account + ($request->coupon - $coupon->coupon);
                } else {
                    $new = $previous->account - ($coupon->coupon - $request->coupon);
                }

                $now = DB::table('users')->where('username', $request->user)->update(['account'=>$new]);

                return redirect('/coupon')->with('success', "User $request->user's coupon is successfully updated from $coupon->coupon to $request->coupon!");
            }
            else{
                return redirect('/coupon')->with('danger', 'Unable to bought the coupon now! Try again later please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    function deleteCoupon($id){

        if (Auth::user()->role =='Admin') {
            $return_code=0;
            $return_code = DB::table('coupons')->where('id', $id)->delete();
            if ($return_code == 1) {
                return redirect('/coupon')->with('success', 'coupon information successfully Deleted!');
            }
            else{
                return redirect('/coupon')->with('danger', 'Unable to Delete the coupon information!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    public static function profile($user)
    {
        $profile = DB::table('users')->where('username', $user)->first();
        return $profile->profile;
    }

    function searchUser($input){
        $Ucount = DB::table('coupons')->where('username', 'Like', '%'.$input.'%')
                                  ->orWhere('email', 'Like', '%'.$input.'%')->count();
        $coupons = DB::table('coupons')->where('username', 'Like', '%'.$input.'%')
                                  ->orWhere('email', 'Like', '%'.$input.'%')->get();

        $output='';
        $no=0;
        foreach ($coupons as $coupon) {
            $no++;
            if(Auth::user()->role == "Admin"){
                $output .= '<tr>
                <th scope="row">'.++$no.'</th>
                <td><a href="/userProfile/'.$coupon->id.'" style="text-decoration: none;"><img src="../image/userProfile/'.App\Http\Controllers\couponController::profile($coupon->user).'" style="width: 30px; height: 30px;" alt=""> '. $coupon->user .'</a></td>
                <td >'.$coupon->coupon.'</td>
                <td>'.$coupon->cb_date.'</td>
                <td>
                  @if (Auth::user()->role == "Admin")
                  <a href="#" data-bs-toggle="modal" data-bs-target="#viewcoupon'.$coupon->id.'" title="Edit User"
                    style="border:none; border-radius:4px; text-decoration: none; height: 25px; margin-right: 8px;"
                    class="get-btn  btn-sm pr-2 pl-2 bx bx-show"></a>

                    <a href="deleteCoupon/'.$coupon->id.'" title="Delete Coupon"
                        class="btn btn-danger btn-sm bi bi-trash-fill"
                        style="height: 25px;"
                        onclick="return deleteCoupon();"></a>
                  @endif

                  </td>
                </tr>';
            }
        }

        return response()->json(['output'=>$output], 200);
    }

}
