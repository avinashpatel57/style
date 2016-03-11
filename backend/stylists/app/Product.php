<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    protected $fillable = ['agency_id', 'merchant_id', 'name', 'price', 'product_link', 'upload_image',
        'image_name','merchant_product_id', 'brand_id', 'category_id', 'gender_id', 'primary_color_id', 'secondary_color_id'];

    public $timestamps = true;

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function merchant(){
        return $this->belongsTo('App\Merchant', 'merchant_id');
    }

    public function brand(){
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function gender(){
        return $this->belongsTo('App\Models\Lookups\Gender', 'gender_id');
    }

    public function looks(){
        return $this->belongsToMany('App\Look', 'looks_products');
    }

    public function primary_color(){
        return $this->belongsTo('App\Models\Lookups\Color', 'primary_color_id');
    }

    public function secondary_color(){
        return $this->belongsTo('App\Models\Lookups\Color', 'secondary_color_id');
    }

    public function stylist(){
        return $this->belongsTo('App\Stylist', 'stylish_id');
    }

}