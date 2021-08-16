<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Music;
use Yajra\Datatables\Facades\Datatables;
use Redirect;
use Storage;
use CURLFILE;
use getID3;
use Lang;
use App\MusicGenre;
use Config;
class MusicController extends Controller
{
     public function __construct()
    {
        $env  = env('APP_ENV');
        
        $configurationDetails = \App\InmateConfiguration::get();
        if ($env == 'prod') {

            $pro_api_url = $configurationDetails->where('key','pro_api_url')->first();
            Config::set('axxs.apiurl', $pro_api_url['content']);

        }elseif ($env == 'qa') {

           $qa_api_url = $configurationDetails->where('key','qa_api_url')->first();
            Config::set('axxs.apiurl', $qa_api_url['content']);
        }elseif ($env == 'test') {

            $test_api_url = $configurationDetails->where('key','test_api_url')->first();
            Config::set('axxs.apiurl', $test_api_url['content']);
        } else{

            $test_api_url = $configurationDetails->where('key','test_api_url')->first();
            Config::set('axxs.apiurl', $test_api_url['content']);
        }
        
    }


    /**
     * Function for load Music Add UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function addMusicUI(Request $request){
        $api_url  = config('axxs.apiurl');
        $music_genre = MusicGenre::all();
        
        return View('music.addmusic',compact('api_url','music_genre'));
    }


    public function musicFileDetails(Request $request){
        $tmpfile = $_FILES['music_file']['tmp_name'];

        $getID3 = new \getID3();
        $audioData = $getID3->analyze($tmpfile);
        //dd($audioData);
        $fileInfo=[];
        try {
            if (isset($audioData['tags'])) {
            if(isset($audioData['tags']['id3v1'])) {
                $fileInfo['data'] = array(
                    'name' => isset($audioData['tags']['id3v1']['title'][0]) ? $audioData['tags']['id3v1']['title'][0] : '',
                    'artist' => isset($audioData['tags']['id3v1']['artist'][0]) ? $audioData['tags']['id3v1']['artist'][0] : '' ,
                    'genre' => isset($audioData['tags']['id3v1']['genre'][0]) ? $audioData['tags']['id3v1']['genre'][0] : (isset($audioData['tags']['id3v2']['genre'][0]) ? $audioData['tags']['id3v2']['genre'][0] : (isset($audioData['tags']['id3v3']['genre'][0]) ? $audioData['tags']['id3v3']['genre'][0] : ''))
                ); 
            }
            else if(isset($audioData['tags']['id3v2'])) {
                $fileInfo['data'] = array(
                    'name' => isset($audioData['tags']['id3v2']['title'][0]) ? $audioData['tags']['id3v2']['title'][0] : '' ,
                    'artist' => isset($audioData['tags']['id3v2']['artist'][0]) ? $audioData['tags']['id3v2']['artist'][0] : '',
                    'genre' => isset($audioData['tags']['id3v2']['genre'][0]) ? $audioData['tags']['id3v2']['genre'][0] :(isset($audioData['tags']['id3v3']['genre'][0]) ? $audioData['tags']['id3v3']['genre'][0] : '')
                );
            }
            else if(isset($audioData['tags']['id3v3'])) {
                $fileInfo['data'] = array(
                    'name' => isset($audioData['tags']['id3v3']['title'][0]) ? $audioData['tags']['id3v3']['title'][0] : '' ,
                    'artist' => isset($audioData['tags']['id3v3']['artist'][0]) ? $audioData['tags']['id3v3']['artist'][0] : '',
                    'genre' => isset($audioData['tags']['id3v3']['genre'][0]) ? $audioData['tags']['id3v3']['genre'][0] : '' 
                );  
            }
        }
            $fileInfo['code'] = 200;
            $fileInfo['status'] = Lang::get('common.success');
            $fileInfo['msg'] = 'Music file details';
            return $fileInfo;
        } catch (\Exception $e) {
            $fileInfo['code'] = 400;
            $fileInfo['status'] = Lang::get('common.failure');
            $fileInfo['msg'] = Lang::get('common.went_wrong');
            return $fileInfo;
        }
        
    }



    /**
     * Create a new service instance after a valid registration
     * 
     * @param  
     *                                
     * @return json The id of newly registered service keyed id in Response
     */
   
    public function registerMusic(Request $request){
        $data = $request->input();
        $tmpfile = $_FILES['music_file']['tmp_name'];
        $filename = basename($_FILES['music_file']['name']);

        $cfile = new CURLFILE($tmpfile, $_FILES['music_file']['type'], $filename);

        $payLoadData = array(
            'song_name'  => $data['song_name'],
            'artist_name' => $data['artist_name'],
            'genre_name' => $data['genre_name'],
            'music_file' => $cfile
        );
        $url =  config('axxs.apiurl').config('axxs.url.addMusic');
        $curl = curl_init();

        curl_setopt_array($curl, array(
           CURLOPT_URL => $url,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 600,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => $payLoadData,
           CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: multipart/form-data",
          )
       ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
           echo "cURL Error #:" . $err;
        } else {
            return json_decode($response,TRUE);
        }
    }

    public function musicList(){
    }

    /**
     * Function for load music list UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return view
    */

    public function musicListUI(){
        $api_url  = config('axxs.apiurl');
        $music_genre = MusicGenre::all();
    	return View('music.musiclist',compact('api_url','music_genre'));
    }

    /**
     *Inactive music list UI
     *
     *@param Null
     *
     *@return view
    */
    public function inactiveMusiclist(){
        $api_url  = config('axxs.apiurl');
        $music_genre = MusicGenre::all();
        return View('music.musiclist',compact('api_url','music_genre'));
    }

    /**
     *Function for edit music
     *
     *@param $id music id
     *
     *@return view
    */

    public function editMusic($id){
        $url =  config('axxs.apiurl').config('axxs.url.editMusic').'/'.$id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => $id,
            CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "content-type: application/json",
             ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
             return json_decode($response,TRUE);
        }
    }

    /**
     *Function for update music
     *
     *@param $object music id
     *
     *@return view
    */
    public function updateMusic(Request $request){
        $req = $request->all();

        $id = $req['music_id'];
        $payLoadData = array(
            'song_name'  => $req['song'],
            'artist_name' => $req['artist'],
            'genre_name' => $req['genre']
        );

        $url =  config('axxs.apiurl').config('axxs.url.updateMusic').'/'.$id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $payLoadData,
            CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "content-type: multipart/form-data",
           )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $status = json_decode($response,TRUE);
            if($status['Code'] == 200){
               return Redirect::back()->with('flash_message', 'Music updated successfully');
            } else {
                return Redirect::back()->with('flash_message', 'Something went wrong!');
            }
        }
    }

    /**
     *Function for delete music
     *
     *@param $object music id
     *
     *@return json
    */
    public function deleteMusic($id){
        $url =  config('axxs.apiurl').config('axxs.url.deactivate').'/'.$id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $id,
            CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "content-type: application/json",
             ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
             return json_decode($response,TRUE);
        }
    }

    public function makeMusicactive($id){
        $url =  config('axxs.apiurl').config('axxs.url.activate').'/'.$id;
        $curl = curl_init();
            
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $id,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response,TRUE);
        }
    }
}
