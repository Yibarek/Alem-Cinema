<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\validator;
use DataTables;

class userController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function show(){
        $users = DB::table('users')->orderBy('id')->get();
        return view('users.users', ['users'=>$users]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15', 'min:10'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    function add(Request $request){
        if(Auth::user()->role == 'Admin'){
            $return_code=0;
            $profile="";
            $return_code=0;
            $new_image_name='';
// return substr($request->phone, 0, 4);
            if (strpos((substr($request->phone, 0, 4)), '+251')) {
                return redirect('/user')->with('danger', 'Invalid Phone Number Try again later. Phone No must start with "+251" and 9 additional numbers.');
            }

            if($request->file('profile')){
                $file= $request->file('profile');
                $new_image_name= date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('/image/userProfile'), $new_image_name);
            }

            if ($new_image_name == "") {
                $profile = 'profile.png';
            }else {
                $profile = $new_image_name;
            }


            $data[]='';
            $data['username']=$request->username;
            $data['email']=$request->email;
            $data['phone']=$request->phone;
            $data['password']=$request->password;

            if (validator($data) == true) {
                $return_code = DB::table('users')->insertOrIgnore([
                    'email'=>$request->email,
                    'username'=>$request->username,
                    'profile'=>$profile,
                    'role'=>$request->role,
                    'phone'=>$request->phone,
                    'account'=>0,
                    'password' => Hash::make($data['password']),
                ]);
            }
            // $id = DB::table('users')->max('id');
            if ($return_code == 1) {
                return redirect('/user')->with('success', 'User successfully registered to the system!');
            }
            else{
                return redirect('/user')->with('danger', 'Unable to register the user! Try again please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    function edit(Request $request, $id){
        $profile_image = DB::table('users')->where('id', $id)->first();
        if (strpos((substr($request->phone, 0, 4)), '+251')) {
            return redirect('/user')->with('danger', 'Invalid Phone Number Try again later. Phone No must start with "+251" and 9 additional numbers.');
        }
        $userProfile = DB::table('users')->where('id', $id)->first();
        if(Auth::user()->role == 'Admin' || Auth::user()->id == $id){
            $profile="";
            $return_code=0;
            $new_image_name='';

            if($request->file('profile1')){
                $file= $request->file('profile1');
                $new_image_name= date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('/image/userProfile'), $new_image_name);
            }

            if ($new_image_name == "") {
                if ($request->pi1 == '') {
                    $profile = $profile_image->profile;
                }
                else {
                    $profile = $request->pi1;
                }
            }else {
                $profile = $new_image_name;
            }

            $data[]='';
            $data['username']=$request->username;
            $data['email']=$request->email;
            $data['phone']=$request->phone;

            if (validator($data)) {
                $response = DB::table('users')->where('id', $id)->Update([
                    'username'=>$request->username,
                    'role'=>$request->role,
                    'email'=>$request->email,
                    'phone'=>$request->phone,
                    'profile'=>$profile,
                ]);
            }
            if ($response == 1) {
                return redirect('/user')->with('success', 'User are Updated successfully!');
            }
            else{
                return redirect("/user")->with('danger', 'Nothing is Updated. Please try again');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function editProfile(Request $request){
        $profile_image = DB::table('users')->where('id', Auth::user()->id)->first();

        if(Auth::user()->role == 'Admin' || Auth::user()->id == Auth::user()->id){
            $return_code=0;
            $profile="";
            $return_code=0;
            $new_image_name='';

            if($request->file('profile')){
                $file= $request->file('profile');
                $new_image_name= date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('/image/userProfile'), $new_image_name);
            }

            if ($new_image_name == "") {
                $profile = 'profile.png';
            }else {
                $profile = $new_image_name;
            }
            // $profile="";
            // $return_code=0;
            // $new_image_name='';
            // $identify=date('YmdHi');
            // if($request->file('profile')){
            //     $file= $request->file('profile');
            //     $new_image_name= $identify.$file->getClientOriginalName();
            //     $file-> move(public_path('/image/userProfile'), $new_image_name);
            // }

            // if ($new_image_name == "") {
            //     if ($request->pi == '') {
            //         $profile = $profile_image->profile;
            //     }
            //     else {
            //         $profile = $request->pi;
            //     }
            // }else {
            //     $profile = $new_image_name;
            // }
            // return $profile;
            $data[]='';
            $data['username']=$request->username;
            $data['email']=$request->email;
            $data['phone']=$request->phone;

            if (validator($data)) {
                $response = DB::table('users')->where('id', Auth::user()->id)->Update([
                    'username'=>$request->username,
                    'role'=>$request->role,
                    'email'=>$request->email,
                    'phone'=>$request->phone,
                    'profile'=>$profile,
                ]);
            }
            if ($response == 1) {
                return redirect('/profile')->with('success', 'Profile are Updated successfully!');
            }
            else{
                return redirect("/profile")->with('danger', 'Nothing is Updated. Please try again');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function delete($id){
        $role='';
        $user = DB::table('users')->where('id', $id)->first();

        if ($user->role != 'Admin' && Auth::user()->role =='Admin') {
            $return_code=0;
            $return_code = DB::table('users')->where('id', $id)->delete();
            if ($return_code == 1) {
                return redirect('/users')->with('success', 'User information successfully Deleted!');
            }
            else{
                return redirect('/users')->with('danger', 'Unable to Delete the user information!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function searchUser($input){
        $Ucount = DB::table('users')->where('username', 'Like', '%'.$input.'%')
                                  ->orWhere('email', 'Like', '%'.$input.'%')->count();
        $users = DB::table('users')->where('username', 'Like', '%'.$input.'%')
                                  ->orWhere('email', 'Like', '%'.$input.'%')->get();
        // $users = DB::table('users')->where('id', '1')
        //                           ->orWhere('id', '2')->get();
        $output='';
        $no=0;
        foreach ($users as $user) {
            $no++;
            if(Auth::user()->role == "Admin" && $user->role != 'Admin'){
                $output .= '<tr>
                <td>'. $no.'</td>
                <td><img src="../image/userProfile/'.$user->profile.'" width="30" height="30" class="rounded-circle" style="margin-right: 4px;"></td>
                <td>'. $user->username.'</td>
                <td>'. $user->account.'</td>
                <td>'. $user->email.'</td>
                <td>'. $user->phone.'</td>
                <td>'. $user->role.'</td>
                <td>'.
              '<a href="#" data-bs-toggle="modal" data-bs-target="#viewuser'.$user->id.'" title="Edit User"
                style="border:none; border-radius:4px; text-decoration: none; height: 25px; margin-right: 8px;"
                class="get-btn  btn-sm pr-2 pl-2 bx bx-show"></a>'.

                '<a href="deleteUser/'.$user->id.'" title="Delete User"
                    class="btn btn-danger btn-sm bi bi-trash-fill"
                    style="height: 25px;"
                    onclick="return myFunction();"></a>'.
              '</td> </tr>';
            }elseif(Auth::user()->role == "Admin" && $user->role == 'Admin') {
                $output .= '<tr>
                <td>'. $no.'</td>
                <td><img src="../image/userProfile/'.$user->profile.'" width="30" height="30" class="rounded-circle" style="margin-right: 4px;"></td>
                <td>'. $user->username.'</td>
                <td>'. $user->account.'</td>
                <td>'. $user->email.'</td>
                <td>'. $user->phone.'</td>
                <td>'. $user->role.'</td>
                <td>'.'</td>
            </tr>';
            }

        }

        return response()->json(['output'=>$output], 200);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $curPassword =$request->input('currentPassword');
        $newPassword = $request->input('renewPassword');

        if (Hash::check($curPassword, $user->password)) {
            DB::table('users')->where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->renewPassword)
            ]);

            return redirect('/profile')->with('success', 'You have changed the Password successfully!');
        }
        else
        {
            return redirect('/profile')->with('danger', 'unable to change the Password! please try again.');
        }
    }

    function detail(){
        return view('users.detailU');
    }

    public function myaccount(){
        $coupons = DB::table('coupons')->where('user', Auth::user()->username)->orderBy('id')->get();
        $users = DB::table('users')->orderBy('username')->get();
        return view('myaccount', ['coupons'=>$coupons, 'users'=>$users]);
    }

}
