<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PALHeader extends Model
{
    use HasFactory;

    protected $table = 'PAL_Header';
    protected $primaryKey = 'PALHeaderID';
    public $timestamps = false;

    protected $fillable = [
        'ItemNo',
        'InventoryTag',
        'Article',
        'Description',
        'Barcode',
        'AcquisitionDate',
        'Unit',
        'UnitCost',
        'QtyPerPropertyCard',
        'QtyPhysicalCount',
        'Accountable',
        'AccumDepreciation',
        'AccumImpairmentLosses',
        'CarryingAmt',
        'PARNo',
        'PARDate',
        'ACCode',
        'FundCode',
        'DivCode',
        'Building',
        'Location',
        'Condition',
        'Remarks',
        'Collector',
        'DateCollected',
        'Status',
        'DateCreated',
        'DateModified'
    ];

    protected $casts = [
        'UnitCost' => 'decimal:2',
        'QtyPerPropertyCard' => 'decimal:2',
        'QtyPhysicalCount' => 'decimal:2',
        'AccumDepreciation' => 'decimal:2',
        'AccumImpairmentLosses' => 'decimal:2',
        'CarryingAmt' => 'decimal:2',
        'AcquisitionDate' => 'date',
        'PARDate' => 'date',
        'DateCollected' => 'date',
        'DateCreated' => 'datetime',
        'DateModified' => 'datetime'
    ];

    // Relationships
    public function details()
    {
        return $this->hasMany(PALDetails::class, 'PALHeaderID');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'DivCode');
    }

    public function accountable()
    {
        return $this->belongsTo(Employee::class, 'Accountable');
    }

    public function collector()
    {
        return $this->belongsTo(Employee::class, 'Collector');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status');
    }

    // Scopes
    public function scopeByItemNo($query, $itemNo)
    {
        return $query->where('ItemNo', $itemNo);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'X');
    }

    // Methods
    public static function generateItemNo($year = null)
    {
        $year = $year ?? date('Y');
        $sequence = self::whereYear('DateCreated', $year)->count() + 1;
        
        return 'ITEM-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }
}
