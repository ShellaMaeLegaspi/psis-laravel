<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PALDetails extends Model
{
    use HasFactory;

    protected $table = 'PAL_Details';
    protected $primaryKey = 'PALDetailsID';
    public $timestamps = false;

    protected $fillable = [
        'PALHeaderID',
        'ItemNo',
        'Qty',
        'Component',
        'MainArticleDescription',
        'ORNo',
        'Amount',
        'Remarks',
        'Status'
    ];

    protected $casts = [
        'Qty' => 'decimal:2',
        'Amount' => 'decimal:2'
    ];

    // Relationships
    public function header()
    {
        return $this->belongsTo(PALHeader::class, 'PALHeaderID');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status');
    }

    // Scopes
    public function scopeByHeader($query, $headerId)
    {
        return $query->where('PALHeaderID', $headerId);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'X');
    }
}
