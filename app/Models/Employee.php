<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = ['matricule', 'name', 'designation', 'department_id', 'user_id','image_path'];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function historyEntries()
    {
        return $this->hasMany(HistoryEntry::class);
    }



    protected static $rules = [
        'department_id' => 'integer', // Assurez-vous qu'il s'agit d'un entier
    ];

}
