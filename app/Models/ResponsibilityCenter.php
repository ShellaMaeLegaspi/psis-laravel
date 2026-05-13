<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsibilityCenter extends Model
{
    use HasFactory;

    protected $table = 'ResponsibilityCenters';
    protected $primaryKey = 'RCCD';
    public $timestamps = false;

    protected $fillable = [
        'RCCD',
        'RCDesc',
        'DivCode',
        'Acronym',
        'InActive'
    ];

    // Relationships
    public function division()
    {
        return $this->belongsTo(Division::class, 'DivCode');
    }

    public function abstracts()
    {
        return $this->hasMany(AbstractModel::class, 'RespoCenter');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'RCCD');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('InActive', 0);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('RCDesc');
    }
}
