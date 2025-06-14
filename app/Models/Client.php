<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {
    use HasFactory;

    protected $fillable = ['last_name', 'address', 'user_id', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    } 

}
