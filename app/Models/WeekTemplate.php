<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'timetable', 'user_id'];

    protected function casts(): array
    {
        return [
            'timetable' => 'json',
        ];
    }

    protected function timetable(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->convertEmptyArraysToObjects(json_decode($value, true)),
        );
    }

    private function convertEmptyArraysToObjects(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (is_array($value) && empty($value)) {
                $value = (object) []; // Преобразуем пустой массив в объект
            } elseif (is_array($value)) {
                $value = $this->convertEmptyArraysToObjects($value); // Рекурсивно обрабатываем вложенные массивы
            }
        }
        return $data;
    }
    
    public function events()
    {
        return $this->belongsTo(User::class);
    }
}
