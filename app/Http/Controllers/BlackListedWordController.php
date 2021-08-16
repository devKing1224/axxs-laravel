<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\BlackListedWord;
use App\AllowUrl;
use Auth;
use Validator;
use Lang;

/**
 * To handle blacklist word, assign,create,edit etc
 * @category BlackListedWordController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

class BlackListedWordController extends Controller
{
      protected function guard() {
        return Auth::guard('admin');
    }

    public function __construct() {
//        $this->middleware(['auth']);
        $this->middleware(['auth', 'common']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
  /**
     * Display a  listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $role = Auth::user()->role_id;
        $blackListedWords = BlackListedWord::where('addedbyuser_id',null);
        if($role == 2){
            $blackListedWords =$blackListedWords->orWhere('addedbyuser_id' ,Auth::user()->id);
        }
        $blackListedWords = $blackListedWords->orderBy('blacklisted_words','ASC')->get();
         
	
        return view('blacklist.index', array('blackListedWords' =>$blackListedWords));
    }



   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        //Get all blacklist word and pass it to the view

             if (isset($request->id)) {
            $objBlackListedWord = new BlackListedWord();
            $blacklistedinfo = $objBlackListedWord->getBlacklistedinfo($request->id);

            if ($blacklistedinfo) {
                return View('blacklist.create', array('blacklistedinfo' => $blacklistedinfo));
            } else {
                return redirect(route('blacklist.index'));
            }
        } else {
            return View('blacklist.create');
        }
    }

    

 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addblacklistWord(Request $request) {	 
		try {
		    $data = $request->all(); 
		    $rules = array(
		        'blacklisted_words' => 'required|unique:black_listed_words',
		       
		    );
		 
		    $validate = Validator::make($data, $rules);

		    if ($validate->fails()) {
		        return response()->json(array(
		                    'Code' => 400,
		                    'Status' => Lang::get('common.success'),
		                    'Message' => $validate->errors()->all(),
		                    'Response' => array('id' => null)
		        ));
		    } else {
		        $black_list_word_data = BlackListedWord::create(['blacklisted_words' => $data['blacklisted_words'],'addedbyuser_id' => $data['addedbyuser_id'] ]);
		        return response()->json(array(
		                    'Code' => 201,
		                    'Status' => Lang::get('common.success'),
		                    'Message' => Lang::get('facility.blacklisted_word_created'),
		                    'Response' => array('black_list_word_data' => $black_list_word_data)
		        ));
		     }

		} catch (Exception $ex) {
		    return errorLog($ex);
		}

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBlacklist(Request $request) {

	      $data = $request->input(); 
	        $rules = array(
	            'blacklisted_words' => 'required',
	        );

        	$validate = Validator::make($data, $rules);

	        if ($validate->fails()) {
	            return response()->json(
	                            array(
	                                'Status' => \Lang::get('common.success'),
	                                'Code' => 400,
	                                'Message' => $validate->errors()->all()
	                            )
	            );
	        } else {
   
	        $blacklistUpdate = BlackListedWord::where('id', $data['id'])->update(['blacklisted_words'=> $data['blacklisted_words']]);

	                return response()->json(
	                            array(
	                                'Code' => 200,
	                                'Status' => \Lang::get('common.success'),
	                                'Message' => \Lang::get('facility.blacklisted_word_edit_success') 
	                            )
	                );
	            }
	        }

		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function deleteBlacklisted(Request $request) {

            try {
            $data = $request->id;

            $objBlackListedWord = new BlackListedWord();
            $deleteblacklisted = $objBlackListedWord->deleteBlacklistedWord($data);
     
            if (isset($deleteblacklisted) && !empty($deleteblacklisted)) {

                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.blacklist_delete'),
                                )
                );
            }else{
            	     return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('facility.blaclist_delete_error')
                            )
            );

            }
       
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

/**
     * Display a  listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function urlList() {

            $objurl = new AllowUrl(); 
            $urls = $objurl->getUrlList();

            return view('blacklist.urllist', array('urls' =>$urls));
        }


 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addUrl(Request $request) {
        //Get all url and pass it to the view

             if (isset($request->id)) {
            $objUrl = new AllowUrl();
            $urlinfo = $objUrl->getUrlinfo($request->id);

            if ($urlinfo) {
                return View('blacklist.addurl', array('urlinfo' => $urlinfo));
            } else {
                return redirect(route('blacklist.urllist'));
            }
        } else {
            return View('blacklist.addurl');
        }
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAllowUrl(Request $request) {     
        try {
            $data = $request->all(); 

                $url = AllowUrl::where('url', $data['url'])
                                ->where('is_deleted', 1)->exists();

                   if($url) {
                            return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 400,
                                    'Message' =>'This URl is already available in inactive list'
                            ));
                    } 



            $rules = array(
                'url' => 'required|unique:allow_urls',
               
            );
         
            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Code' => 400,
                            'Status' => Lang::get('common.success'),
                            'Message' => $validate->errors()->all(),
                            'Response' => array('id' => null)
                ));
            } else {
                $add_url_data = AllowUrl::create(['url' => $data['url']]);
                return response()->json(array(
                            'Code' => 201,
                            'Status' => Lang::get('common.success'),
                            'Message' => Lang::get('facility.url_created'),
                            'Response' => array('black_list_word_data' => $add_url_data)
                ));
             }

        } catch (Exception $ex) {
            return errorLog($ex);
        }

    }
 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUrl(Request $request) {

          $data = $request->input(); 
            $rules = array(
                'url' => 'required',
            );

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => $validate->errors()->all()
                                )
                );
            } else {
   
            $UrlUpdate = AllowUrl::where('id', $data['id'])->update(['url'=> $data['url']]);

                    return response()->json(
                                array(
                                    'Code' => 200,
                                    'Status' => \Lang::get('common.success'),
                                    'Message' => \Lang::get('facility.url_edit_success') 
                                )
                    );
                }
            }

/**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function deleteUrl(Request $request) {

            try {
            $data = $request->id;

            $objurl = new AllowUrl();
            $deletedurl = $objurl->deleteUrl($data);
     
            if (isset($deletedurl) && !empty($deletedurl)) {

                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.url_delete'),
                                )
                );
            }else{
                     return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('facility.url_delete_error')
                            )
            );

            }
       
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

/**
     * Display a  listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function inactivList() {

            $objurl = new AllowUrl(); 
            $urls = $objurl->getInactiveUrlList();

            return view('blacklist.inactiveurllist', array('urls' =>$urls));
        }

 /**
     * Create function for update url details behalf on  id.
     *
     * @param object Request $request The  id keyed id, 
     * 
     * @return NULL
     */
    public function activeUrl(Request $request) {
        $data = $request->input();
        $rules = array(
            'id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $updateData = array(
                'is_deleted' => config('axxs.active'),
      
            );
            $urlUpdateInfo = AllowUrl::where(array('id' => $data['id']))->update($updateData);
            if (isset($urlUpdateInfo) && !empty($urlUpdateInfo)) {

                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('facility.update_url'),
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('facility.update_url_error')
                ));
            }
        }
    }


}
