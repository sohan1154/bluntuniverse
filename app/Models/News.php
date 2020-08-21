<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;
use App\Models\Category;

class News extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'category_id', 'title', 'slug', 'author', 'image', 'description', 'is_verified', 'status'
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function category() {
      return $this->belongsTo(Category::class, 'category_id');
    }

    public function user() {
      return $this->belongsTo(Category::class, 'user_id');
    }

}