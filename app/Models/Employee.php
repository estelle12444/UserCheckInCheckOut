<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['matricule', 'name', 'designation', 'department_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function historyEntries()
    {
        return $this->belongsToMany(HistoryEntry::class);
    }
}
