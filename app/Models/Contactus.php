<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Contactus extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'contact_us';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'mobile', 'email', 'subject', 'message'
  ];

}