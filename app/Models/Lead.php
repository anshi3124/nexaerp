<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','company','email','phone','source','status','expected_value','follow_up_date','notes','assigned_to','created_by'];
}