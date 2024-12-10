<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'user_id', 
        'title', 
        'teacher', 
        'week_day', 
        'lesson_number'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
