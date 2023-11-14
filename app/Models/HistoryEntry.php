<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'in_confidence',
        'out_confidence',
        'localisation_id',
        'employee_id',
        'time_at_in',
        'time_at_out',
        'day_at_in',
        'day_at_out'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
