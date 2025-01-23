<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 
        'room', 
        'name',
        'teacher', 
        'order',
        'date',
        'homework',
        'notes',
        'test',
        'project',
        'colloquium',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value;

        // Вычисляем и устанавливаем значение для day
        $this->attributes['day'] = Carbon::parse($value)->format('l');
    }
    
}
