<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = [
        'template_id','name','email','country','description','photos','price',
        'status','checkout_session_id'
    ];
    protected $casts = ['photos' => 'array'];
    public function template(){ return $this->belongsTo(\App\Models\Template::class); }
}
