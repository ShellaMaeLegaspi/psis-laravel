<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRISUPHeader extends Model
{
    use HasFactory;

    protected $table = 'PRISUP_Header';
    protected $primaryKey = 'PRISUPHeaderID';
    public $timestamps = false;

    protected $fillable = [
        'Year',
        'Series',
        'PRISUPControlNo',
        'PRISUPNo',
        'DivCode',
        'RespoCenter',
        'Status',
        'PreparedBy',
        'DateCreated',
        'InspectedBy',
        'ApprovedBy',
        'ReceivedBy',
        'TrackingNo',
        'Remarks',
        'DateModified',
        'InspectedDate',
        'ApprovedDate',
        'ReceivedDate'
    ];

    protected $casts = [
        'DateCreated' => 'datetime',
        'DateModified' => 'datetime',
        'InspectedDate' => 'datetime',
        'ApprovedDate' => 'datetime',
        'ReceivedDate' => 'datetime'
    ];

    // Relationships
    public function details()
    {
        return $this->hasMany(PRISUPDetails::class, 'PRISUPHeaderID');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'DivCode');
    }

    public function responsibilityCenter()
    {
        return $this->belongsTo(ResponsibilityCenter::class, 'RespoCenter');
    }

    public function preparedBy()
    {
        return $this->belongsTo(Employee::class, 'PreparedBy');
    }

    public function inspectedBy()
    {
        return $this->belongsTo(Employee::class, 'InspectedBy');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'ApprovedBy');
    }

    public function receivedBy()
    {
        return $this->belongsTo(Employee::class, 'ReceivedBy');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('Status', $status);
        }
        return $query->where('Status', $status);
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('PreparedBy', $employeeId);
    }

    public function scopeByDivision($query, $divCode)
    {
        return $query->where('DivCode', $divCode);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('Year', $year);
    }

    // Methods
    public static function generateControlNo($year = null)
    {
        $year = $year ?? date('Y');
        $sequence = self::where('Year', $year)->max('Series') + 1;
        
        return 'PRISUP-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function markAsDone()
    {
        $this->update([
            'Status' => 'D',
            'DateModified' => now()
        ]);
    }

    public function returnDocument()
    {
        $this->update([
            'Status' => 'R',
            'DateModified' => now()
        ]);
    }
}
