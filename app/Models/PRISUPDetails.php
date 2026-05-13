<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRISUPDetails extends Model
{
    use HasFactory;

    protected $table = 'PRISUP_Details';
    protected $primaryKey = 'PRISUPDetailsID';
    public $timestamps = false;

    protected $fillable = [
        'PRISUPHeaderID',
        'ItemID',
        'PropertyNo',
        'Quantity',
        'Unit',
        'Description',
        'UnitCost',
        'TotalCost',
        'AccountableOfficer',
        'PARNo',
        'Remarks',
        'Status'
    ];

    protected $casts = [
        'Quantity' => 'decimal:2',
        'UnitCost' => 'decimal:2',
        'TotalCost' => 'decimal:2'
    ];

    // Relationships
    public function header()
    {
        return $this->belongsTo(PRISUPHeader::class, 'PRISUPHeaderID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID');
    }

    public function accountableOfficer()
    {
        return $this->belongsTo(Employee::class, 'AccountableOfficer');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status');
    }

    // Scopes
    public function scopeByHeader($query, $headerId)
    {
        return $query->where('PRISUPHeaderID', $headerId);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'X');
    }
}
