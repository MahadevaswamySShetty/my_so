<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['user_id','message','is_read'];

    public function files(){
    	return $this->hasMany(App\File::class, 'chat_id');
    }

    public function users(){
    	return $this->belongsTo(User::class, 'user_id');
    }
}
