<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    use HasFactory;
    
    protected $guarded = ["id"];

    protected $fillable = [
        'lecturer_id',    
        'university_id',  
        'nidn',
        'status',
        'rejection_reason'
    ];

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }

    public function university(){
        return $this->belongsTo(University::class);
    }
}
