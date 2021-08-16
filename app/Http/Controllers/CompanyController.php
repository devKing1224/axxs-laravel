<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Validator;
use Yajra\Datatables\Facades\Datatables;
class CompanyController extends Controller
{
    public function addCompanyUI(Request $request){
    	if (isset($request->id)) {
    	    $obj_cmpny = new Company();
    	    $company = $obj_cmpny->getCompanyAllInfor($request->id);
    	    if ($company) {
    	        return View('company.addcompany', array('company' => $company));
    	    } else {
    	        return redirect(route('cmpy.list'));
    	    }
    	} else {
    	    return View('company.addcompany');
    	}
    }

    public function registerCompany(Request $request){
    	$data = $request->input();
    	$rules = array(
    	    'name' => 'required|unique:companies',
    	    'description' => 'required',
    	);
    	$validate = Validator::make($data, $rules);
    	if ($validate->fails()) {
    	    return response()->json(
    	        array(
    	                        'Code' => 400,
    	                        'Status' => \Lang::get('common.success'),
    	                        'Message' => $validate->errors()->all(),
    	                    )
    	    );
    	} else {
    		$create_cmpny = Company::create($data);
    		if ($create_cmpny->id) {
    			$result= array();
                    $result['code'] = 201; 
                     return $result;
    		}
    		    	}

    }


    public function compnayListUI(){
    	return view('company.company_list');
    }

    public function getCompanydata(){
    		$cmpny = Company::where('is_deleted',0);

    		return Datatables::of($cmpny)
    		->addIndexColumn()
            ->addColumn('name', function($cmpny){
                return '<a href="javascript:void(0)"><span data-toggle="tooltip" data-placement="right" title ="'.$cmpny->description.'">'.$cmpny->name.'<span></a>';
            })
    		->addColumn('action', function ($cmpny) {
    			return '<a href="'.route('cmpy.add',$cmpny->id).'"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;</a>'
    	               ;
                      /* <a href="JavaScript:Void(0)" onClick="deletefa('.$cmpny->id.')"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" title="Delete"></i></a>&nbsp;&nbsp;</a>
                       <a href="'.route('fadmin.facilitylist',$cmpny->id).'"><i class="fa fa-file-excel-o" data-toggle="tooltip" title="Download Facility List"></i></a>'*/
    	               })
    		->blacklist(['DT_RowIndex','action'])
    		->make(true);
    }

    public function getCompanylist(){
        $cmpny_data = Company::select('id','name','description')->where('is_deleted',config('axxs.active'))->get();
                $data['company_data'] = $cmpny_data;
                
                return $data;
    }

    public function updateOrg(Request $request){
        $data = $request->all();
        $rules = array(
                'name' => 'required|unique:companies,name,' . $data['id'] . ',id',
                'description' => 'required',
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
            //update code
            $updateData = array(
                'name' => $data['name'],
                'description' => $data['description']
            );
            $updateOrg = Company::where(array('id' => $data['id']))->update($updateData);
            if (isset($updateOrg) && !empty($updateOrg)) {

                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => 'Organization Edit Success',
                                )
                );
            } else {
                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => 'Organization Edit Unsuccess'
                                )
                );
            }
        }
    }
}
