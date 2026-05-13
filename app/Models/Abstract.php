<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    use HasFactory;

    protected $table = 'Abstract_Header';
    protected $primaryKey = 'AbstractHeaderID';
    public $timestamps = false;

    protected $fillable = [
        'AbstractControlNo',
        'AbstractNo',
        'TrackingNo',
        'DivCode',
        'RespoCenter',
        'DateReceived',
        'DateCreated',
        'PRNo',
        'TotalAmount',
        'ProjectCode',
        'PostIBDate',
        'PhilGEPSReferenceNo',
        'OpenOfBidsDate',
        'SupplierID',
        'Particulars',
        'ExpectedDateOfDelivery',
        'DateNumbered',
        'EncodedBy',
        'PreparedBy',
        'CertifiedBy',
        'ApprovedBy',
        'EvaluatedBy_TWG',
        'EvaluatedBy_EndUser',
        'EvaluatedBy_Chair',
        'EvaluatedBy_ViceChair',
        'EvaluatedBy_Member1',
        'EvaluatedBy_Member2',
        'EvaluatedBy_Member3',
        'Status'
    ];

    protected $casts = [
        'TotalAmount' => 'decimal:2',
        'DateReceived' => 'datetime',
        'DateCreated' => 'datetime',
        'PostIBDate' => 'datetime',
        'OpenOfBidsDate' => 'datetime',
        'ExpectedDateOfDelivery' => 'datetime',
        'DateNumbered' => 'datetime'
    ];

    // Relationships
    public function details()
    {
        return $this->hasMany(AbstractDetails::class, 'AbstractHeaderID');
    }

    public function ppmpDetails()
    {
        return $this->hasManyThrough(PPMPDetails::class, AbstractDetails::class, 'PPMPDetailsID');
    }

    public function ppmpHeader()
    {
        return $this->hasManyThrough(
            PPMPHeader::class,
            AbstractDetails::class,
            'PPMPDetailsID',
            'AbstractHeaderID',
            'PPMPHeaderID'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'DivCode');
    }

    public function responsibilityCenter()
    {
        return $this->belongsTo(ResponsibilityCenter::class, 'RespoCenter');
    }

    public function encodedBy()
    {
        return $this->belongsTo(Employee::class, 'EncodedBy');
    }

    public function preparedBy()
    {
        return $this->belongsTo(Employee::class, 'PreparedBy');
    }

    public function certifiedBy()
    {
        return $this->belongsTo(Employee::class, 'CertifiedBy');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'ApprovedBy');
    }

    public function evaluatedByTWG()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_TWG');
    }

    public function evaluatedByEndUser()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_EndUser');
    }

    public function evaluatedByChair()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_Chair');
    }

    public function evaluatedByViceChair()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_ViceChair');
    }

    public function evaluatedByMember1()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_Member1');
    }

    public function evaluatedByMember2()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_Member2');
    }

    public function evaluatedByMember3()
    {
        return $this->belongsTo(Employee::class, 'EvaluatedBy_Member3');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Status');
    }

    // Scopes
    public function scopeWithDetails($query)
    {
        return $query->with(['details', 'supplier', 'division', 'encodedBy', 'preparedBy', 'certifiedBy', 'approvedBy', 'status']);
    }

    public function scopeByStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('Status', $status);
        }
        return $query->where('Status', $status);
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where(function($q) use ($employeeId) {
            $q->where('EncodedBy', $employeeId)
              ->orWhere('PreparedBy', $employeeId)
              ->orWhere('CertifiedBy', $employeeId)
              ->orWhere('ApprovedBy', $employeeId)
              ->orWhere('EvaluatedBy_TWG', $employeeId)
              ->orWhere('EvaluatedBy_EndUser', $employeeId)
              ->orWhere('EvaluatedBy_Chair', $employeeId)
              ->orWhere('EvaluatedBy_ViceChair', $employeeId)
              ->orWhere('EvaluatedBy_Member1', $employeeId)
              ->orWhere('EvaluatedBy_Member2', $employeeId)
              ->orWhere('EvaluatedBy_Member3', $employeeId);
        });
    }

    public function scopeByCriteria($query, $criteria)
    {
        if (isset($criteria['AbstractControlNo']) && $criteria['AbstractControlNo']) {
            $query->where('AbstractControlNo', 'like', '%' . $criteria['AbstractControlNo'] . '%');
        }
        if (isset($criteria['AbstractNo']) && $criteria['AbstractNo']) {
            $query->where('AbstractNo', 'like', '%' . $criteria['AbstractNo'] . '%');
        }
        if (isset($criteria['DivCode']) && $criteria['DivCode']) {
            $query->where('DivCode', $criteria['DivCode']);
        }
        if (isset($criteria['RespoCenter']) && $criteria['RespoCenter']) {
            $query->where('RespoCenter', $criteria['RespoCenter']);
        }
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('DateCreated', '<=', $criteria['DateTo']);
        }
        
        return $query;
    }
}
