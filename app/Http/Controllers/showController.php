<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class showController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function show(){
        $shows = DB::table('shows')->orderBy('id')->get();
        $movies = DB::table('movies')->orderBy('id')->get();
        return view('show.show', ['shows'=>$shows, 'movies'=>$movies]);
    }

    public function addShow(Request $request)
    {
        if(Auth::user()->role == 'Multimedia Specialist'){
            $return_code=0;
            $return_code = DB::table('shows')->insertOrIgnore([
                'movie'=>$request->selectVal,
                'show_date'=>$request->show_date,
                'show_time'=>$request->show_time.':00',
                'vip'=>$request->vip,
                'normal'=>$request->normal,
                'upcomming'=>$request->radio_value,
                'status'=>$request->radio_value,
            ]);
            if ($return_code == 1) {
                DB::table('movies')->where('title', $request->selectVal)->update([
                    'vip'=>$request->vip,
                    'normal'=>$request->normal,
                    'show_date'=>$request->show_date,
                    'show_time'=>$request->show_time,
                ]);
                DB::table('seats')->where('movie', $request->selectVal)->delete();
                return redirect('/show')->with('success', "Show $request->name successfully registered!");
            }
            else{
                return redirect('/show')->with('danger', 'Unable to register the show!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    public function updateShow(Request $request, $show_id)
    {
        if(Auth::user()->role == 'Multimedia Specialist'){
            $return_code=0;
            $return_code = DB::table('shows')->where('id', $show_id)->update([
                'movie'=>$request->selectVal,
                'show_date'=>$request->show_date,
                'show_time'=>$request->show_time,
                'vip'=>$request->vip,
                'normal'=>$request->normal,
                'upcomming'=>$request->radio_value1,
                'status'=>$request->radio_value1,
            ]);
            if ($return_code == 1) {
                DB::table('movies')->where('title', $request->selectVal)->update(
                    ['vip'=>$request->vip,
                    'show_date'=>$request->show_date,
                    'show_time'=>$request->show_time,
                     'normal'=>$request->normal]
                );
                return redirect('/show')->with('success', "Show for $request->selectVal is successfully Updated!");
            }
            else{
                return redirect('/show')->with('danger', 'Unable to update the show!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function deleteShow($id){

        if (Auth::user()->role =='Multimedia Specialist') {
            $return_code=0;
            $return_code = DB::table('shows')->where('id', $id)->delete();
            if ($return_code == 1) {
                return redirect('/show')->with('success', 'Show information successfully Deleted!');
            }

            else{
                return redirect('/show')->with('danger', 'Unable to Delete the show information!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    function searchShow($input){
        $Ucount = DB::table('shows')->where('show_date', 'Like', '%'.$input.'%')
                                    ->orWhere('movie', 'Like', '%'.$input.'%')->count();

        $Shows = DB::table('shows')->where('show_date', 'Like', '%'.$input.'%')
                                    ->orWhere('movie', 'Like', '%'.$input.'%')->get();

        $output='';
        $no=0;
        foreach ($Shows as $Show) {
            $no++;
            if(Auth::user()->role == "Multimedia Specialist"){
                $output .= '<tr>
                <td>'. $no.'</td>
                <td>'. $Show->movie.'</td>
                <td>'. $Show->show_date.'</td>
                <td>'. $Show->show_time.'</td>
                <td>'. $Show->vip.'</td>
                <td>'. $Show->normal.'</td>
                <td>'.
              '<a href="#" data-bs-toggle="modal" data-bs-target="#viewcatagory'.$Show->id.'" title="Edit Show"
                style="border:none; border-radius:4px; text-decoration: none; height: 25px; margin-right: 8px;"
                class="get-btn  btn-sm pr-2 pl-2 bx bx-show"></a>'.

                '<a href="deleteShow/'.$Show->id.'" title="Delete Show"
                    class="btn btn-danger btn-sm bi bi-trash-fill"
                    style="height: 25px;"
                    onclick="return myFunction();"></a>'.
              '</td> </tr>';
            }
        }

        return response()->json(['output'=>$output], 200);
    }

    public function profile($user)
    {
        $profile = DB::table('users')->where('username', $user)->first();
        return $profile->profile;
    }
}
