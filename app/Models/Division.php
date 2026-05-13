<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $table = 'Divisions';
    protected $primaryKey = 'DivCode';
    public $timestamps = false;

    protected $fillable = [
        'DivCode',
        'DivName',
        'InActive'
    ];

    // Relationships
    public function abstracts()
    {
        return $this->hasMany(AbstractModel::class, 'DivCode');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'DivCode');
    }

    public function responsibilityCenters()
    {
        return $this->hasMany(ResponsibilityCenter::class, 'DivCode');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('InActive', 0);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('DivName');
    }
}
