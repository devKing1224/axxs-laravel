<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\InmateReportHistory;
use App\User;

class InmateReportHistoryController extends Controller
{
    
    
    /**
     * Create a new inmate report login history instance after a valid registration
     * 
     * @param object Request $request The service permission details keyed email,
     *                           
     * @return json The id of newly report history instance keyed id in Response
    */
    public function registerInmateReportHistory (Request $request)
    {
        $data = $request->input();
        
        $rules = array(
             'email' => 'required',
        );
        
        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                'Code' => 400,
                'Status' => \Lang::get('common.success'),
                'Message' => $validate->errors()->all(),
            ));
        } else {
            $obj_user = User::where('email',$data['email'])->first();
            $inmate_report_history_insert = InmateReportHistory::create([
                'inmate_id' => $obj_user->id,
                'status' => config('axxs.status.unblock'),
                'report_time' => gmdate("Y-m-d H:i:s"),
            ]);
            if (isset($inmate_report_history_insert->id) && !empty($inmate_report_history_insert->id)) {
                return response()->json(array(
                    'Code' => 201,
                    'Message' => \Lang::get('common.success'),
                    'Status' => \Lang::get('report.report_created'),
                    'Data' => array('id' => $inmate_report_history_insert->id)
                ));
            } else {
                return response()->json(array(
                'Code' => 401,
                'Message' => \Lang::get('common.success'), 
                'Status' => array(\Lang::get('report.report_not_created')),
                ));
            }
        }
    }
    
     /**
     * Function for load inmate login report list UI.
     * 
     * @return NULL
    */
    public function getLoginInmateReportListUI($inmate_id = null) { 

        $roleID = Auth::user()->role_id;
        if(Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])){
          if(Auth::user()->hasRole('Facility Staff')) {
               $facilityID =  Auth::user()->admin_id;
          } else {
              $facilityID =  Auth::user()->id;
          }
           $objInmateReportHistory = new InmateReportHistory();
           $inmateReportHistoryList = $objInmateReportHistory->getInmateLoginReportList($facilityID,$inmate_id);

            return View('inmate/inmatereportloginlist', ['inmateReportHistoryList' => $inmateReportHistoryList,'inmate_id' =>$inmate_id]);
        }   else {
            $objInmateReportHistory = new InmateReportHistory();
            $inmateReportHistoryList = $objInmateReportHistory->getAllInmateLoginReportList($inmate_id);
            return View('inmate/inmatereportloginlist', ['inmateReportHistoryList' => $inmateReportHistoryList,'inmate_id' =>$inmate_id]);
        }
    }
    
     /**
     * Create function for soft delete inmate report login details behalf on report id.
     *
     * @param object Request $request The report id keyed report_id.
     * 
     * @return NULL
    */
    public function deleteReport(Request $request)
    {
        $data = $request->id;
        $objInmateReportHistory = new InmateActivityHistory();
        $deletInmateLoginReport = $objInmateReportHistory->deleteInmateLoginReport($data);
        if(isset($deletInmateLoginReport) && !empty($deletInmateLoginReport)) {
            return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 200,
                'Message' => \Lang::get('inmate.inmate_delete'),
            ));
        } else {
            return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 400,
                'Message' => \Lang::get('service.inmate_delete_error')
            ));
        }
    }
}
