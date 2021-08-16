<?php
/**
  * Handling the API Configuration Setting.
  *
  * @param integer $facility_id , $api_name, Illuminate\Http\Request $request
  *
  * @return void
  */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APISetting;
use DB;
use App\BlacklistedMusic;
class APIController extends Controller
{   
    /**
     * Show the form for setting configuration for News API.
     *
     * @return \Illuminate\View\View
     */
    public function apiNews(){
    	$api_key = DB::table('api_keys')->where('api_name','newsapi')->first();
        $facility = \App\Facility::select('id','facility_name')->where('is_deleted',0)->get()->toArray();
        $categories = DB::table('news_category')->get()->toArray();
        return view('ex_api.manage_api',compact('api_key','facility', 'categories'));
    }

    /**
     * Update API .
     *
     * @return \Illuminate\View\View
     */
    public function updateAPI(Request $request){
    	if ($request['api_name'] == 'newsapi') {
    		$api_name = $request['api_name'];

    		unset($request['api_name']);
    		unset($request['_token']);
    		
    		foreach ($request->all() as $key => $value) {
                $api_data = [
                   'api_name' => $api_name,
                   'key_name' => $key,
                   'value' => $value
               ];
               APISetting::updateOrCreate(['api_name' => $api_name,'key_name'=>$key ],$api_data);
           }    		
       }

       return redirect()->back()->with('message', 'API Updated Successfully!');
   }

    /**
     * Show the form for setting Music API.
     *
     * @return \Illuminate\View\View
     */
    public function apiSoundcloud(){
     $api_key = DB::table('api_keys')->where('api_name','soundcloud')->first();
     $facility = \App\Facility::select('id','facility_name')->where('is_deleted',0)->get()->toArray();
     $genre = DB::table('music_genres')->get()->toArray();
     
     return view('ex_api.managesoundcloud',compact('api_key','facility','genre'));

 }

    /**
     * Get the facility configuration setting for music API.
     * @param $facility_id
     *
     * @return array $data
     */
    public function getSCfacilitySetting($facility_id){
        $SC_setting = \App\SoundCloudAPI::where('facility_id',$facility_id)->first();

        if ($SC_setting == null) {
            $data['msg'] = 'configuration not set yet';
            $data['status'] = 'sucess';
            $data['data'] = $SC_setting;
            $data['code'] = 404;
            # code...
        } else{
            $data['msg'] = 'configuration details';
            $data['status'] = 'sucess';
            $data['data'] = $SC_setting;
            $data['code'] = 200;
        }
        return  $data;

    }

    /**
     * Show the music Player.
     * @param $facility_id
     *
     * @return \Illuminate\View\View
     */
    public function soundcloudView($facility_id){
        $client_id = DB::table('api_keys')->select('api_key')->where('api_name','soundcloud')->first();
        $client_id = $client_id->api_key;
        $setting = \App\SoundCloudAPI::where('facility_id',$facility_id)->first();
        $bl_word = \App\BlacklistedMusic::select('keyword')->where('facility_id',$facility_id)->first();//blacklisted_word
        $bl_word = $bl_word['keyword'];
        return view('soundcloud',compact('client_id','setting','bl_word'));
    }

    /**
     * Add Music configuration for facility.
     * @param \Illuminate\Http\Request
     *
     * @return json
     */
    public function addSCconfig(Request $request){
        $data =$request->all();
        $data['genres'] =implode(',', $data['genres']);
        $flight = \App\SoundCloudAPI::updateOrCreate(['facility_id' => $data['facility_id'] ],$data);
    }

    /**
     * Get News configuration setting for facility.
     * @param $facility_id
     *
     * @return array $SC_setting
     */
    public function getSCfacilityNewssetting($facility_id){
        $SC_setting = \App\NewsSetting::where('facility_id',$facility_id)->first();
        return $SC_setting;

    }

    /**
     * Add News Configuration setting for facility.
     * @param \Illuminate\Http\Request
     *
     * @return json
     */
    public function addNewsconfig(Request $request){
        $data =$request->all();
        $data['category'] = implode(',', $data['category']);
        $flight = \App\NewsSetting::updateOrCreate(['facility_id' => $data['facility_id'] ], $data);
    }

    /**
     * Function to get genres.
     *
     * @return json
     */
    public function getGenres(){
        return DB::table('music_genres')->get()->toArray();
    }

    /**
     * Function for adding new genres.
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function addGenres(Request $request){

        $validator = \Validator::make($request->all(), [
            'genres' => 'required|unique:music_genres'
        ]);

        if ($validator->fails()) {
         $error = $validator->errors()->first();

         return array(
            'msg' => $error,
            'status' => 'error',
            'code' => 409
        );
     } else{
        $insert = \App\MusicGenre::insert(['genres' =>$request['genres'] ]);
        return array(
            'msg' => 'Genre added Successfully',
            'status' => 'success',
            'code' => 200
        );
    }
}

    /**
     * function for getting blacklisted word.
     * @param $facility_id
     *
     * @return array
     */
    public function getMusicblWord($facility_id){
        $bl_word = BlacklistedMusic::select('keyword')->where('facility_id',$facility_id)->first();
        
        return response()->json(array(
            'Status' => \Lang::get('inmate.blacklisted_word'),
            'Code' => 200,
            'Message' => \Lang::get('common.success'),
            'Data' => $bl_word['keyword']
        ));
    }

    /**
     * Function for adding blacklisted word.
     * @param \Illuminate\Http\Request ,$facility_id
     *
     * @return array
     */
    public function addBLword(Request $req,$facility_id){
        $bl_word = BlacklistedMusic::select('keyword')->where('facility_id',$facility_id)->first();
        $a = $bl_word['keyword'];
        $b = $req['bl_word'];
        if ($a != null) {
            $c = $a.','.$b;
        } else{
            $c = $b;
        }
        try {
            BlacklistedMusic::updateOrCreate(['facility_id' => $facility_id ],['keyword' => $c]);
            return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 200,
                'Message' =>\Lang::get('inmate.blacklisted_word_add') ,
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'Status' => \Lang::get('common.error'),
                'Code' => 400,
                'Message' => $e->getMessage(),
            ));
        }      
    }

    /**
     * function for updating blacklisted word.
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function update_bl_word(Request $req){
        try {
            if ($req['bl_word'] != null) {
                $req['bl_word'] = implode(',',$req['bl_word']);
            }
            BlacklistedMusic::updateOrCreate(['facility_id' =>$req['facility_id']],['keyword' => $req['bl_word'] ]);
            return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 200,
                'Message' =>\Lang::get('inmate.blacklisted_word_update') ,
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'Status' => \Lang::get('common.error'),
                'Code' => 400,
                'Message' => $e->getMessage(),
            ));
        }      
    }
}
