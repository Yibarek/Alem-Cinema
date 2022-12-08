<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class catagoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function show(){
        $catagories = DB::table('catagories')->orderBy('id')->get();
        return view('catagory.catagories', ['catagories'=>$catagories]);
    }

    public function addCatagory(Request $request)
    {
        if(Auth::user()->role == 'Multimedia Specialist'){
            $return_code=0;
            $return_code = DB::table('catagories')->insertOrIgnore([
                'name'=>$request->name,
                'description'=>$request->description,
            ]);
            if ($return_code == 1) {
                return redirect('/catagories')->with('success', "Catagory $request->name successfully registered!");
            }
            else{
                return redirect('/catagories')->with('danger', 'Unable to register the catagory!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function searchCatagory($input){
        $Ucount = DB::table('catagories')->where('name', 'Like', '%'.$input.'%')->count();
        $catagories = DB::table('catagories')->where('name', 'Like', '%'.$input.'%')->get();
        // $users = DB::table('users')->where('id', '1')
        //                           ->orWhere('id', '2')->get();
        $output='';
        $no=0;
        foreach ($catagories as $catagory) {
            $no++;
            if(Auth::user()->role == "Multimedia Specialist"){
                $output .= '<tr>
                <td>'. $no.'</td>
                <td>'. $catagory->name.'</td>
                <td>'. $catagory->description.'</td>
                <td>'.
              '<a href="#" data-bs-toggle="modal" data-bs-target="#viewcatagory'.$catagory->id.'" title="Edit Catagory"
                style="border:none; border-radius:4px; text-decoration: none; height: 25px; margin-right: 8px;"
                class="get-btn  btn-sm pr-2 pl-2 bx bx-show"></a>'.

                '<a href="deleteCatagory/'.$catagory->id.'" title="Delete Catagory"
                    class="btn btn-danger btn-sm bi bi-trash-fill"
                    style="height: 25px;"
                    onclick="return myFunction();"></a>'.
              '</td> </tr>';
            }
        }

        return response()->json(['output'=>$output], 200);
    }

    public function updateCatagory(Request $request, $catagory_id)
    {
        if(Auth::user()->role == 'Multimedia Specialist'){
            $return_code=0;
            $return_code = DB::table('catagories')->where('id', $catagory_id)->update([
                'name'=>$request->name,
                'description'=>$request->description,
            ]);
            if ($return_code == 1) {
                return redirect('/catagories')->with('success', "Catagory $request->name successfully Updated!");
            }
            else{
                return redirect('/catagories')->with('danger', 'Unable to update the catagory!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function deleteCatagory($id){

        if (Auth::user()->role =='Multimedia Specialist') {
            $return_code=0;
            $return_code = DB::table('catagories')->where('id', $id)->delete();
            if ($return_code == 1) {
                return redirect('/catagories')->with('success', 'Catagory information successfully Deleted!');
            }

            else{
                return redirect('/catagories')->with('danger', 'Unable to Delete the catagory information!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    public function profile($user)
    {
        $profile = DB::table('users')->where('username', $user)->first();
        return $profile->profile;
    }
}
