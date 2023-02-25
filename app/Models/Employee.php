<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'position_id',
        'date_of_employment',
        'phone_number',
        'email',
        'salary',
        'photo',
        'admin_created_id',
        'admin_updated_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    protected $appends = [
      'photo_url',
    ];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('photos')->exists($this->photo)){
          return Storage::disk('photos')->url($this->photo);
        }

        return asset('noimage.png');
    }
}
