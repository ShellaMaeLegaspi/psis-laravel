<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIRUPDetails extends Model
{
    use HasFactory;

    protected $table = 'IIRUP_Details';
    protected $primaryKey = 'IIRUPDetailsID';
    public $timestamps = false;

    protected $fillable = [
        'IIRUPHeaderID',
        'ItemID',
        'Quantity',
        'Unit',
        'Description',
        'UnitCost',
        'TotalCost',
        'AccountableOfficer',
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
        return $this->belongsTo(IIRUPHeader::class, 'IIRUPHeaderID');
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
        return $query->where('IIRUPHeaderID', $headerId);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'X');
    }
}
