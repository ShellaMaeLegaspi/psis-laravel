<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbstractDetails extends Model
{
    use HasFactory;

    protected $table = 'Abstract_Details';
    protected $primaryKey = 'AbstractDetailsID';
    public $timestamps = false;

    protected $fillable = [
        'AbstractHeaderID',
        'PPMPDetailsID',
        'ItemID',
        'Quantity',
        'UnitCost',
        'TotalAmount',
        'Remarks',
        'Status'
    ];

    protected $casts = [
        'UnitCost' => 'decimal:2',
        'TotalAmount' => 'decimal:2'
    ];

    // Relationships
    public function header()
    {
        return $this->belongsTo(AbstractModel::class, 'AbstractHeaderID');
    }

    public function ppmpDetails()
    {
        return $this->belongsTo(PPMPDetails::class, 'PPMPDetailsID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status');
    }

    // Scopes
    public function scopeByHeader($query, $headerId)
    {
        return $query->where('AbstractHeaderID', $headerId);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'X');
    }
}
