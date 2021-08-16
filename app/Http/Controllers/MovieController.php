<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Movie;
use Yajra\Datatables\Facades\Datatables;
use Redirect;
use Storage;
class MovieController extends Controller
{
    /**
     * Function for load service Add UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function addMovieUI(Request $request) {

        if (isset($request->id)) {
            if (Auth::user()->hasRole('Facility Admin')) {

                $objService = new Service();
                $serviceInfo = $objService->getfacilityServiceInfo($request->id, config('axxs.active'), Auth::user()->id);
                if ($serviceInfo) {
                    $serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))->get();
                    return View('service.addservice', ['serviceInfo' => $serviceInfo, 'serviceCategory' => $serviceCategory]);
                } else {
                    return redirect(route('service.list'));
                }
            } else {

                $serviceInfo = Service::where('id', $request->id)->first();

                if ($serviceInfo) {
                    $serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))->get();
                    return View('service.addservice', ['serviceInfo' => $serviceInfo, 'serviceCategory' => $serviceCategory]);
                } else {
                    return redirect(route('service.list'));
                }
            }
        } else {
        	
            //$serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))->get();
            return View('movie.addmovie');
        }
    }

    /**
     * Create a new service instance after a valid registration
     * 
     * @param object Request $request The service details keyed name,
     *                              base_url, logo_url, type, charge       
     *                                
     * @return json The id of newly registered service keyed id in Response
     */
    public function registerMovie(Request $request) {
        $data = $request->input();

        
    
        if ($request->hasFile('logo_url')) {
        	$rules = array(
                'name' => 'required|unique:movies,name,NULL,id',
                'movie_url' => 'required',
            );
            
        } else {
            $rules = array(
                'name' => 'required|unique:movies,name,NULL,id',
                'movie_url' => 'required',
                'logo_url' => 'required',
            );
        }

        $validate = Validator::make($data, $rules);
         


        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            if ($request->hasFile('logo_url')) {
            $image = $request->file('logo_url');
            $logo_url = time() . '.' . $image->getClientOriginalExtension();
            $t = Storage::disk('s3Images')->put('movie/'.$logo_url , file_get_contents($image), 'public');
            $data['logo_url'] = Storage::disk('s3Images')->url('movie/'.$logo_url);
            /*$destinationPath = public_path('images/movie');
            $image->move($destinationPath, $logo_url);
            $data['logo_url'] = asset('public/images/movie') . "/" . $logo_url;*/
            $data['img_name'] = $logo_url;
            
        }
            $service_insert = Movie::create([
                        'name' => $data['name'],
                        'movie_url' => $data['movie_url'],
                        'logo_url' => $data['logo_url'],
                        'img_name' => $data['img_name']
            ]);

            if (isset($service_insert->id) && !empty($service_insert->id)) {
                \App\AllowUrl::create(['url'=>$data['movie_url']]);
                return response()->json(array(
                            'Status' => \Lang::get('service.service_created'),
                            'Code' => 201,
                            'Message' => \Lang::get('common.success'),
                            'Data' => array('id' => $service_insert->id)
                ));
            } else {
                return response()->json(array(
                            'Status' => array(\Lang::get('service.service_not_created')),
                            'Code' => 401,
                            'Message' => \Lang::get('common.success'),
                ));
            }
        }
    }

    /**
     * Function for load movie list UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return view
     */
    public function movieListUI(){
    	return View('movie.movielist');
    }

    public function movieList(){
    	$get_movie = Movie::where('is_deleted',0);

    	return Datatables::of($get_movie)
    	->addIndexColumn()
    	->addColumn('action', function ($get_movie) {
                return '<a onClick="editMovie('.$get_movie->id.')" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                   <a onClick="deleteMovie('.$get_movie->id.')" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</a>   ';})

    	->addColumn('logo', function($get_movie){
    		return '<img src="'.$get_movie->logo_url.'" height="42" width="42">';
    	})
    	->addColumn('movie_url', function($get_movie){
    		return '<a  href="'.$get_movie->movie_url.'"><p style="max-width:80ch;">'.$get_movie->movie_url.'</p></a>';
    	})
        ->blacklist(['DT_RowIndex','logo','action'])
    	->make(true);
    }

    public function playMovies($id = null){
        if (isset($id)) {
            $movie = Movie::where('id',$id)->first();
            return view('movie.stream',['movie' => $movie]);
        }
        $movieList = Movie::where('is_deleted', '0')->get();
        return view('movie.playmovie',['movielist' => $movieList]);
    }

    /**
     *Function for edit movie
     *
     *@param $id movie id
     *
     *@return view
    */
    public function editMovie($movie_id){
        $movie = Movie::where('id',$movie_id)->first();
        return $movie;
    }

    /**
     *Function for update movie
     *
     *@param $object movie id
     *
     *@return view
    */
    public function updateMovie(Request $request){
        $req = $request->all();
        $rules = array(
                'movie_name' => 'required|unique:movies,name,'.$req['movie_id'],
                'movie_url' => 'required',
            );
        $validate = Validator::make($req, $rules);
         


        if ($validate->fails()) {
            return Redirect::back()->withErrors($validate->errors());
        } else {
            $req = $request->all();
            $data = array(
                'name' => $req['movie_name'],
                'movie_url' => $req['movie_url']
            );
            if ($request->hasFile('logo_image')) {
                $image = $request->file('logo_image');
                $logo_url = time() . '.' . $image->getClientOriginalExtension();
                $t = Storage::disk('s3Images')->put('movie/'.$logo_url , file_get_contents($image), 'public');
                $data['logo_url'] = Storage::disk('s3Images')->url('movie/'.$logo_url);
                /*$destinationPath = public_path('images/movie');
                $image->move($destinationPath, $logo_url);
                $data['logo_url'] = asset('public/images/movie') . "/" . $logo_url;*/
                $data['img_name'] = $logo_url;
                /*$old_image = public_path().'/images/movie'.'/'.$req['old_imageurl'];
                if (file_exists($old_image)) {
                    unlink($old_image);
                }*/
                
                
            }
            

            Movie::where('id', $req['movie_id'])->update($data);
            return Redirect::back()->with('flash_message', 'Movie updated successfully');
        }
        
    }

    /**
     *Function for delete movie
     *
     *@param $object movie id
     *
     *@return json
    */
    public function deleteMovie($id){
        $movie = Movie::find($id);
        if ($movie) {
            \App\AllowUrl::where('url',$movie->movie_url)->update(['is_deleted' => 1]);
            Movie::where('id',$id)->update(['is_deleted' => 1]);
        }
    }

    /**
     *Inactive movie list UI
     *
     *@param Null
     *
     *@return view
    */
    public function inactiveMovielist(){
        return View('movie.movielist');
    }

    public function getInactivemovie(){
            $get_movie = Movie::where('is_deleted',1);

            return Datatables::of($get_movie)
            ->addIndexColumn()
            ->addColumn('action', function ($get_movie) {
                    return '<a onClick="makeMovieActive('.$get_movie->id.')" style="cursor:pointer" ><i class="glyphicon glyphicon-thumbs-up"></i></a>';})

            ->addColumn('logo', function($get_movie){
                return '<img src="'.$get_movie->logo_url.'" height="42" width="42">';
            })
            ->addColumn('movie_url', function($get_movie){
                return '<a  href="'.$get_movie->movie_url.'"><p style="max-width:80ch;">'.$get_movie->movie_url.'</p></a>';
            })
            ->blacklist(['DT_RowIndex','logo','action'])
            ->make(true);
    }

    /**
     *Function for making movie active
     *
     *@param $object movie id
     *
     *@return json
    */
    public function makeMovieactive($id){
            $movie = Movie::find($id);
            if ($movie) {
                \App\AllowUrl::where('url',$movie->movie_url)->update(['is_deleted' => 0]);
                Movie::where('id',$id)->update(['is_deleted' => 0]);
            }
    }
}
