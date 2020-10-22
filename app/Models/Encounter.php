<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public $guarded=['id'];
}
