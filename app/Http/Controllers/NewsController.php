<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\APISetting;
use \Illuminate\Support\Facades\Input;
use DB;
use Lang;
class NewsController extends Controller
{	
	public function __construct($header = null)
	{    
        $setting = DB::table('api_keys')->select('api_key')->where('api_name','newsapi')->first();
        $this->api_setting=$setting->api_key;
        
		$this->header = [
    	    'Accept: application/json',
    	    'Content-Type: application/json',
    	    'X-API-Key:' .$this->api_setting
    	];
	}


    public function index($facility_id = null, Request $request = NULL)
    {
        $newsSetting = \App\NewsSetting::where('facility_id', $facility_id)->first();
        
        $post_data = $request->all();
        $assign_category = explode(',', $newsSetting['category']);
        $cat_limit = (int)round($newsSetting['n_limit']/(count($assign_category)));
        //Show the number of news per page
        $news_per_page = $newsSetting['news_per_page'];
        $allow_search = $newsSetting['allow_search'];
        // News filter code part start
        $data = [];
        $article = [];
        $search_cat = '';
        $search_key = '';
        $title = '';
        $msg = '';
        if ($newsSetting == null) {
            $msg = Lang::get('facility.facility_configuration_not_found');
            return view('axxs_news.index',compact('article','title','allow_search','data','facility_id','news_per_page', 'assign_category', 'url','search_cat','search_key','msg'));
            
        }
        if(isset($post_data['n_flag']) && !empty($post_data['n_flag'])) {
            $category = ( $post_data['news_by'] ) ? $post_data['news_by'] : "";
            $title = 'Search results for the category : -'.' '.$category;
            $url = 'https://newsapi.org/v2/top-headlines?country='.$newsSetting['country'];
            $search_cat = $post_data['news_by'];
            if (isset($category)) {
                $url = $url.'&category='.$category;
            }
            if (isset($newsSetting['n_limit'])) {
                    $url = $url.'&pageSize='.$newsSetting['n_limit'];
                }
            $getTopnews = $this->getCurlData($url);
        } else {
        // News filter code part end
        	if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($post_data['n_flag'])) {
        		$title = 'Search Results For-'.' '.$_POST["key"];
                $search_key = $_POST["key"];
        		$s_key = str_replace(' ', '', $_POST["key"]);
        	    $url = 'https://newsapi.org/v2/everything?q='.$s_key;
                if (isset($newsSetting['n_limit'])) {
                    $url = $url.'&pageSize='.$newsSetting['n_limit'];
                }
                $getTopnews = $this->getCurlData($url);
        	    
        	} else {
                //getrequest
        		$title = 'Top HeadLines';
        		$url = 'https://newsapi.org/v2/top-headlines?country='.$newsSetting['country'];

                if (isset($newsSetting['category'])) {
                    foreach($assign_category as $news_cat) {
                        $caturl = $url.'&category='.$news_cat;
                        $finalurl = $caturl.'&pageSize='.$cat_limit;
                        $news = $this->getCurlData($finalurl);
                        $getTopnews[] = $news['articles'];
                    }
                }
                
                foreach ($getTopnews as $key => $value) {
                        foreach ($value as $values) {
                            $article[] = $values;
                        }
                }
                
                
                return view('axxs_news.index',compact('article','title','allow_search','data','facility_id','news_per_page', 'assign_category', 'url','search_cat','search_key','msg'));

        	}
        }
        
        
        if ($getTopnews['status'] == 'error') {
            $data['status'] = $getTopnews['status'];
            $data['code'] = $getTopnews['code'];
        } else {
            $article = $getTopnews['articles'];
        }

        

    	return view('axxs_news.index',compact('article','title','allow_search','data','facility_id','news_per_page', 'assign_category', 'url','search_cat','search_key','msg'));
    }

    public function getCurlData($url){
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
    	    curl_setopt($ch, CURLOPT_URL, $url);
    	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");  
    	   
    	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
    	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 

    	    $results = curl_exec($ch);
    	    return json_decode($results,TRUE);
    }

    public function getNews(Request $request){
       /* dd($request->all());*/
        $data = $request->all();
       // $data['content'] = strip_tags($data['content']);
        $html = new \Html2Text\Html2Text($data['content']);
        $data['content'] = $html->getText();
        
        return view('axxs_news.shownews',compact('data'));
    }
}
