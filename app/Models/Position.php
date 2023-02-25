<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'admin_created_id',
        'admin_updated_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
