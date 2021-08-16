<?php

/**
 * Controller for taking backup which is inactive for 30 days 
 * 
 * PHP version 7.2
 * 
 * @category BackupController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

/**
 * Schedule a backup process for taking backup of inactive data 
 * 
 * @category Backupcontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php/api/inactivedata_backup
 */
class BackupController extends Controller
{

    /**
     * Common function for data backup
     * 
     * @return null
     */
    public function backupDataDailyBasis() 
    {
        $date = date('Y-m-d', strtotime("-1 month"));
        $this->inactiveFacility($date);
        $this->inactiveDevice($date);
        $this->inactiveDetails($date, null);
    }

    /**
     * Function for taking backup datewise
     *
     * @param object Request $date      one  month prior,
     * @param object Request $inmate_id for Inmate ID
     *                                
     * @return null
     */
    public function inactiveDetails($date, $inmate_id) 
    {
        $this->archiveDetails('\App\InmateSMS', $date, $inmate_id);
        $this->archiveDetails('\App\RecievedInmateEmail', $date, $inmate_id);
        $this->archiveDetails('\App\SentInmateEmail', $date, $inmate_id);
        $this->archiveDetails('\App\Family', $date, $inmate_id);
        $this->archiveDetails('\App\InmateActivityHistory', null, $inmate_id);
        $this->archiveDetails('\App\InmateChargesHistory', $date, $inmate_id);
        $this->archiveDetails('\App\InmateLoggedHistory', null, $inmate_id);
        $this->archiveDetails('\App\InmateReportHistory', $date, $inmate_id);
        $this->archiveDetails('\App\InmateDetails', null, $inmate_id);
        $this->archiveDetails('\App\InmateContacts', $date, $inmate_id);
        $this->archiveDetails('\App\ServiceHistory', null, $inmate_id);
        $this->archiveDetails('\App\ServicePermission', null, $inmate_id);
    }

    /**
     * Function for taking backup of inmate dependent data datewise
     *
     * @param object Request $date one month prior
     *                                
     * @return null
     */
    public function inactiveDevice($date) 
    {
        $this->archiveDetails('\App\Device', $date, null);
    }

    /**
     * Function for taking backup of facility and depenedent data datewise
     *
     * @param object Request $date one month prior
     *                                
     * @return null
     */
    public function inactiveFacility($date) 
    {
        $this->archiveFacilityDetails('\App\Facility', $date);
    }

    /**
     * Function for taking backup datewise
     *
     * @param object Request $model     for Table name
     * @param object Request $date      one month prior, 
     * @param object Request $inmate_id Inmate ID,
     *
     * @return null
     */
    public function archiveDetails($model, $date, $inmate_id) 
    {
        $item = new $model;
        $table = $item->getTable();
        if ($inmate_id) {
            $details = $model::where('inmate_id', $inmate_id)->get();
            foreach ($details as $data) {
                $archive_data = $data->toArray();
                DB::table('archive_' . $table)->insert($archive_data);
                $data->delete();
            }
        } elseif ($date) {
            $details = $model::where('is_deleted', 1)->get();
            foreach ($details as $data) {
                $lastdate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->format('Y-m-d');
                if ($lastdate <= $date) {
                    $archive_data = $data->toArray();
                    DB::table('archive_' . $table)->insert($archive_data);
                    $data->delete();
                }
            }
        }
    }

    /**
     * Function for taking backup inmate data datewise
     *
     * @param object Request $model     for Table name
     * @param object Request $date      one month prior, 
     * @param object Request $inmate_id Inmate ID,
     *
     * @return null
     */
    public function archiveInmateDetails($model, $date, $inmate_id) 
    {
        $item = new $model;
        $table = $item->getTable();
        if ($date) {
            $inmatedetails = $model::where('is_deleted', 1)->where('role_id', 4)->get();
            if (count($inmatedetails) > 0) {
                foreach ($inmatedetails as $inmatedata) {
                    $lastdate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $inmatedata->updated_at)->format('Y-m-d');
                    if ($lastdate <= $date) {
                        $this->inactiveDetails(null, $inmatedata->id);
                        $archive_inmate_data = $inmatedata->toArray();
                        DB::table('archive_' . $table)->insert($archive_inmate_data);
                        $inmatedata->delete();
                    }
                }
            }
        } elseif ($inmate_id) {
            $inmatedetails = $model::where('id', $inmate_id)->first();
            if ($inmatedetails) {
                $this->inactiveDetails(null, $inmate_id);
                $this->archivePaymentDetails('\App\PaymentInformation', $inmate_id);
                $archive_inmate_data = $inmatedetails->toArray();
                DB::table('archive_' . $table)->insert($archive_inmate_data);
                $inmatedetails->delete();
            }
        }
    }

    /**
     * Function for taking backup facility and dependent data datewise
     *
     * @param object Request $model for Table name
     * @param object Request $date  one month prior, 
     *
     * @return null
     */
    public function archiveFacilityDetails($model, $date) 
    {
        $item = new $model;
        $table = $item->getTable();
        $facilitydetails = $model::where('is_deleted', 1)->get();
        if (count($facilitydetails) > 0) {
            foreach ($facilitydetails as $facilitydata) {
                $inactivefacility = \App\User::where('id', $facilitydata->facility_user_id)->first();
                $lastdate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $facilitydata->updated_at)->format('Y-m-d');
                if ($lastdate <= $date) {
                    $inmateall = \App\User::where('admin_id', $facilitydata->facility_user_id)->where('role_id', 4)->get();
                    if ($inmateall) {
                        foreach ($inmateall as $inmate) {
                            $this->archiveInmateDetails('\App\User', null, $inmate->id);
                        }
                    }
                    $archive_facility_data = $facilitydata->toArray();
                    DB::table('archive_' . $table)->insert($archive_facility_data);
                    $facilitydata->delete();
                    $archive_user_facility = $inactivefacility->toArray();
                    DB::table('archive_users')->insert($archive_user_facility);
                    $inactivefacility->delete();
                }
            }
        } else {
            $this->archiveInmateDetails('\App\User', $date, null);
        }
    }

    /**
     * Function for taking backup facility and dependent data datewise
     *
     * @param object Request $model     for Table name
     * @param object Request $inmate_id inmate ID, 
     *
     * @return null
     */
    public function archivePaymentDetails($model, $inmate_id) 
    {
        $item = new $model;
        $table = $item->getTable();
        $familyid = \App\Family::where('inmate_id', $inmate_id)->get();
        if (count($familyid) > 0) {
            foreach ($familyid as $fdata) {
                $details = $model::where('family_id', $fdata->id)->get();
                if (count($details) > 0) {
                    foreach ($details as $data) {
                        $archive_payment_data = $data->toArray();
                        DB::table('archive_' . $table)->insert($archive_payment_data);
                        $data->delete();
                    }
                }
            }
        }
    }

}
