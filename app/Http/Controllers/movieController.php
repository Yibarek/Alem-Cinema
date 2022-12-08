<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\validator;
use DateTime;
class movieController extends Controller
{
    public function show(){
        $movies = DB::table('movies')->get();
        $tickets = DB::table('tickets')->get();
        $catagories = DB::table('catagories')->get();
        return view('movies.movies', ['movies'=>$movies, 'Tickets'=>$tickets, 'catagories'=>$catagories, 'cat'=> 'all']);
    }

    public function seats($input){
        $movies = DB::table('movies')->where('id', $input)->get();
        return view('movies.seats', ['movies'=>$movies]);
    }

    public function seat($mov){
        $movies = DB::table('movies')->where('id', $mov)->get();
        return view('movies.ticket', ['movies'=>$movies]);
    }

    public function movieByCatagory($catagory){
        if ($catagory == 'all') {
            $movies = DB::table('movies')->get();
        } else {
            $movies = DB::table('movies')->where('catagory', $catagory)->get();
        }
        $tickets = DB::table('tickets')->get();
        $catagories = DB::table('catagories')->get();
        return view('movies.movies', ['movies'=>$movies, 'Tickets'=>$tickets, 'catagories'=>$catagories, 'cat'=> $catagory]);
    }

    public function now(){
        $date = new DateTime();
        $day = $date -> format('Y-m-d h:i:s');
        return $day;
        // $month = $date -> format('m');
        // $day = $date -> format('d');
        $movies = DB::table('movies')->where('show_date', $day)->get();
        $tickets = DB::table('tickets')->get();
        $catagories = DB::table('catagories')->get();

        return view('movies.movies', ['movies'=>$movies, 'Tickets'=>$tickets, 'catagories'=>$catagories, 'cat'=>'all']);
    }

    public function today(){
        $date = new DateTime();
        $day = $date -> format('Y-m-d');
        // return $day;
        // $month = $date -> format('m');
        // $day = $date -> format('d');
        $movies = DB::table('movies')->where('show_date', $day)->get();
        $tickets = DB::table('tickets')->get();
        $catagories = DB::table('catagories')->get();

        return view('movies.movies', ['movies'=>$movies, 'Tickets'=>$tickets, 'catagories'=>$catagories, 'cat'=>'all']);
    }

    public static function checkTodays($id){
        $date = new DateTime();
        $day = $date -> format('Y-m-d');

        $movies = DB::table('movies')->where('show_date', $day)->where('id', $id)->count();
        if ($movies == 0) {
            return false;
        }
        else {
            return true;
        }

    }

    public function thisWeek(){
        $date = new DateTime();
        $day = $date -> format('Y-m-d');

        $movies = DB::table('movies')->where('show_date', date('Y-m-d',strtotime("+1 days")))
                                     ->orwhere('show_date', date('Y-m-d',strtotime("+2 days")))
                                     ->orwhere('show_date', date('Y-m-d',strtotime("+3 days")))
                                     ->orwhere('show_date', date('Y-m-d',strtotime("+4 days")))
                                     ->orwhere('show_date', date('Y-m-d',strtotime("+5 days")))
                                     ->orwhere('show_date', date('Y-m-d',strtotime("+6 days")))->get();
        $tickets = DB::table('tickets')->get();
        $catagories = DB::table('catagories')->get();

        return view('movies.movies', ['movies'=>$movies, 'Tickets'=>$tickets, 'catagories'=>$catagories, 'cat'=>'all']);
    }

    public function detail($id){
        movieController::view($id);
        $movies = DB::table('movies')->where('id', $id)->get();
        $tickets = DB::table('tickets')->get();
        return view('movies.detailM', ['movies'=>$movies, 'Tickets'=>$tickets]);
    }

    public function addMovie(){
        $catagories = DB::table('catagories')->get();
        return view('movies.addM', ['catagories'=>$catagories]);
    }

    public function editMovie($id){
        $movies = DB::table('movies')->where('id', $id)->get();
        $posters = DB::table('posters')->where('movie', $id)->get();
        $catagories = DB::table('catagories')->get();
        return view('movies.editM', ['catagories'=>$catagories, 'movies'=>$movies, 'posters'=>$posters]);
    }

    public function view($mov_id)
    {
        $mov_view = DB::table('movies')->where('id', $mov_id)->first();
            $mov_view = $mov_view->views + 1;
            $return_code = DB::table('movies')->where('id', $mov_id)->update(
                ['views' => $mov_view]
            );
            // return response()->json(['return_code'=>$return_code], 200);
    }

    public function add(Request $request){
        if(Auth::user()->role == 'Multimedia Specialist'){
            $return_code=0;
            $trailer="";
            $return_code=0;
            $new_video_name='';

            if($request->file('trailer')){
                $file= $request->file('trailer');
                $new_video_name= date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('/video'), $new_video_name);
            }

            if ($new_video_name == "") {
                $trailer = 'trailer.mp4';
            }else {
                $trailer = $new_video_name;
            }


            $return_code = DB::table('movies')->insertOrIgnore([
                'title'=>$request->title,
                'length'=>$request->length,
                'catagory'=>$request->selectVal,
                'producers'=>$request->producers,
                'directors'=>$request->directors,
                'actors'=>$request->actors,
                'released_year'=>$request->released_year,
                'show_time'=>$request->show_date ." ". $request->show_time ,
                'no_of_shows'=>0,
                'views'=>0,
                'likes'=>0,
                'tickets'=>0,
                'description'=>$request->description,
                'trailer'=>$trailer,
                'vip'=>$request->vip,
                'normal'=>$request->normal,
            ]);
            $inserted_id = DB::table('movies')->max('id');
            if ($return_code == 1) {

                $images=array();
                    if($files=$request->file('posters')){
                        $ii=0;
                        foreach($files as $file){
                            $ii++;
                            $name=$file->getClientOriginalName();
                            $file->move(public_path('/image/posters'), $name);
                            $images[]=$name;
                            DB::table('posters')->insertOrIgnore(
                                ['movie'=>$inserted_id,
                                'poster'=>$name]
                            );
                            if ($ii==1) {
                                DB::table('movies')->where('id', $inserted_id)->update(
                                    ['poster'=>$name]
                                );
                            }
                            if($ii==3)
                                break;
                        }
                    }
                }
            if ($return_code == 1) {
                return redirect('/movie')->with('success', 'Movie successfully registered to the system!');
            }
            else{
                return redirect('/movie')->with('danger', 'Unable to register the Movie! Try again please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    public function edit(Request $request, $id){
        if(Auth::user()->role == 'Multimedia Specialist'){
            $return_code=0;
            $trailer="";
            $return_code=0;
            // $new_video_name='';

            // if($request->file('trailer')){
            //     $file= $request->file('trailer');
            //     $new_video_name= date('YmdHi').$file->getClientOriginalName();
            //     $file-> move(public_path('/video'), $new_video_name);
            // }

            // if ($new_video_name == "") {
            //     $trailer = 'trailer.mp4';
            // }else {
            //     $trailer = $new_video_name;
            // }


            $return_code = DB::table('movies')->where('id', $id)->update([
                'title'=>$request->title,
                'length'=>$request->length,
                'catagory'=>$request->selectVal,
                'producers'=>$request->producers,
                'directors'=>$request->directors,
                'actors'=>$request->actors,
                'released_year'=>$request->released_year,
                'description'=>$request->description,
                // 'trailer'=>$trailer,
                // 'vip'=>$request->vip,
                // 'normal'=>$request->normal,
            ]);
            if ($return_code == 1) {
                return redirect('/movie')->with('success', 'Movie successfully updated to the system!');
            }
            else{
                return redirect('/movie')->with('danger', 'Unable to update the Movie! Try again please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    public function like($mov_id, $page){
        $page_redirect = '';
        if ($page == 'welcome') {
            $page_redirect = '/';
        }
        else if ($page == 'detailM') {
            $page_redirect = $page.'/'.$mov_id;
        }
        else {
            $page_redirect = $page;
        }
        $likes = DB::table('likes')->where('user', Auth::user()->username)->where('movie', $mov_id)->count();
        $result = -1;
        if ($likes == 0) {
            $mov_like = DB::table('movies')->where('id', $mov_id)->first();
            $mov_likes = $mov_like->likes + 1;
            DB::table('movies')->where('id', $mov_id)->update(
                ['likes' => $mov_likes]
            );
            $result = 1;
            $like = DB::table('likes')->insertOrIgnore(
                ['user' => Auth::user()->username,
                 'movie'=> $mov_id]
            );
        } else {
            $mov_like = DB::table('movies')->where('id', $mov_id)->first();
            $mov_likes = $mov_like->likes - 1;
            if($mov_likes >= 0){
                DB::table('movies')->where('id', $mov_id)->update(
                    ['likes' => $mov_likes]
                );
                $result = 0;
            }
            $like = DB::table('likes')->where('movie', $mov_id)->where('user', Auth::user()->username)->delete();
        }
        if ($result == 1) {
            return redirect("$page_redirect")->with('success', "Your like has been SAVED successfully!");
        } else {
            return redirect("$page_redirect")->with('success', "Your like has been REMOVED successfully!");
        }
    }

    public static function userLike($mov_id){
        if (DB::table('likes')->where('user', Auth::user()->username)->where('movie', $mov_id)->count() == 0) {
            return false;
        } else {
            return true;
        }

        return true;
    }

    function searchMovie($input){
        $Ucount = DB::table('movies')->where('title', 'Like', '%'.$input.'%')
                                    ->orWhere('producers', 'Like', '%'.$input.'%')
                                    ->orWhere('directors', 'Like', '%'.$input.'%')
                                    ->orWhere('actors', 'Like', '%'.$input.'%')->count();

        $movies = DB::table('movies')->where('title', 'Like', '%'.$input.'%')
                                    ->orWhere('producers', 'Like', '%'.$input.'%')
                                    ->orWhere('directors', 'Like', '%'.$input.'%')
                                    ->orWhere('actors', 'Like', '%'.$input.'%')->get();

        $output='';
        $no=0;


                $movie_list = 0;
            foreach ($movies as $movie){
                    $movie_list++;

                $output .='<div class="col-lg-2 col-md-4 portfolio-item filter-app" >
                <div class="portfolio-wrap" style="max-height: 260px; min-height: 260px;">
                        <label class="movie_title">'.$movie->title.'</label>
                        <img src="../image/posters/'.$movie->poster.'" class="img-fluid" alt="">
                        <div class="portfolio-links">';
                            if (Auth::user()->role == "Multimedia Specialist"){
                                $output .='<div class="d-flex" style="position: absolute; top: -25px; right: 5px;">
                                <a href="/editM/'.$movie->id.'"><i class="ri ri-edit-2-fill p-1" style="font-size: 17px; background-color: #206bcc; color: #fff;" title="Edit Movie"></i></a>
                                <a href="/deleteMovie/'.$movie->id.'"><i class="ri ri-delete-bin-6-line p-1 " style="font-size: 17px; background-color: #cc2620; margin-left: 6px; color: #fff;" title="Delete Movie"></i></a>
                            </div>';
                            }
                            $output .='<a  href="../image/posters/'.$movie->poster.'" data-gallery="portfolioGallery" class="portfolio-lightbox" title="'.$movie->title.'"><i class="bx bx-zoom-in"></i></a>
                            <a href="#" id="'.$movie->id.'" onclick="view'.$movie->id.'()" data-bs-toggle="modal" data-bs-target="#viewTrailer'.$movie->id.'" title="Play Trailer"><i class="bx bx-play"></i></a>

                            <input type="text" id="reserver" name="reserver" style="display: none;">

                            <a href="/detailM/'.$movie->id.'" title="More Details"><i class="bx bx-movie-play"></i></a>
                        </div>
                    </div>
                    <div class="">
                    <label class="p-2" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Views"><i class="bx bx-show" style="color: #3287cc"></i>   <strong id="v'.$movie->id.'">'.$movie->views.'</strong></label>
                    <label class="p-2" title="">
                        <a href="/like/'.$movie->id.'/movie" style="color: #3287cc" title="Likes">';
                            if (movieController::userLike('.$movie->id.')){
                                $output .='<i class=" bx bxs-like"></i>';
                            }
                            else{
                                $output .='<i class=" bx bx-like"></i>';
                            }
                            $output .='</a>  <strong>'.$movie->likes.'</strong></label>';
                        if (ticketController::tickets('.$movie->id.')){
                            $output .='<label class="p-2" title=""><i class="bi bi-ticket-fill" style="color: #3287cc"></i>   <strong>'.$movie->tickets.'</strong>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#ticket'.$movie->title.'" class="bi bi-check-circle-fill" style="color: #3287cc"></a>';
                            if (ticketController::ticketTime('.$movie->title.')){
                                $output .='<a href="/cancelTicket/'.$movie->id.'/movie" title="Cancel Ticket" class="bi bi-x-circle-fill" style="color: #ec7168"></a>';
                            }
                        }
                        else{
                            $output .='<label class="p-2" title=""><i class="bi bi-ticket" style="color: #3287cc"></i>   <strong>'.$movie->tickets.'</strong>';
                            if (ticketController::ticketTime('.$movie->title.')){
                                $output .='<a href="#" data-bs-toggle="modal" data-bs-target="#buyTicket'.$movie->id.'" class="get-btn p-1" type="submit">Buy Ticket</a>';
                            }
                            $output .='</label>';
                        }
                        $output .='</div>
                </div>';
            }
            if ($movie_list == 0){
                $output.='<label class="movie_title" style="text-align: center;">No Movie posts are avaliable.</label>';
            }

        return response()->json(['output'=>$output], 200);
    }

    public static function upcomming($mov_id){
        $movie = DB::table('movies')->where('id', $mov_id)->first();
        $upcomming_count = DB::table('shows')->where('movie', $movie->title)->count();
        if ($upcomming_count > 0) {
            $upcomming = DB::table('shows')->where('movie', $movie->title)->first();
            if ($upcomming->status == "upcomming") {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function delete($id){

        if (Auth::user()->role == 'Multimedia Specialist') {
            $return_code=0;
            $return_code = DB::table('movies')->where('id', $id)->delete();
            if ($return_code == 1) {
                return redirect('/movie')->with('success', 'Movie information successfully Deleted!');
            }
            else{
                return redirect('/movie')->with('danger', 'Unable to Delete the movie information!');
            }
        }
        else {
            return redirect('/403');
        }
    }

}
