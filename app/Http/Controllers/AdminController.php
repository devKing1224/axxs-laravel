<?php

/**
 * Register Admin To manage the Application 
 * 
 * PHP version 7.2
 * 
 * @category Admincontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;

/**
 * Register Admin To manage the Application 
 * 
 * @category Admincontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

class AdminController extends Controller
{
    /**
     * Create a new facility instance after a valid registration
     *
     * @param object Request $request The facility details keyed facility_id, 
     *                                name, email, phone, address_line_1,
     *                                address_line_2, city, state, zip
     *                                total_inmate, facility_admin, password
     *                                
     * @return json The id of newly registered facility keyed id in Response
     */
    public function registerAdmin(Request $request)
    {
            $data = $request->input();
            $rules = array(
                'email' => 'required|unique:facilitys',
                'password' => bcrypt($data['password']),
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
            $user_insert = User::create(
                [
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                    ]
            );
            if (isset($user_insert->id) && !empty($user_insert->id)) {
                return response()->json(
                    array(
                    'Code' => 201,
                    'Message' => \Lang::get('common.success'),
                    'Status' => \Lang::get('inmate.inmate_created'),
                    'Data' => array('id' => $user_insert->id)
                    )
                );
            } else {
                return response()->json(
                    array(
                    'Code' => 401,
                    'Message' => \Lang::get('common.success'), 
                    'Status' => array(\Lang::get('inmate.inmate_not_created')),
                    'Response' => array('id' => null)
                    )
                );
            }
        }
    }
}
