<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseInmate extends Model
{
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['siteId','product', 'customerTransactionId', 'purchaseDate', 'apin', 'amount', 'paymentType', 'transactionId'];
  //  public $timestamps = false;
}
