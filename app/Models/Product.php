<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','sku','category_id','purchase_price','selling_price','stock_quantity','min_stock_level','status','description'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}