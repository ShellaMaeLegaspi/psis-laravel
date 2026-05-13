<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyCard extends Model
{
    use HasFactory;

    protected $table = 'PropertyCard';
    protected $primaryKey = 'PropertyCardID';
    public $timestamps = false;

    protected $fillable = [
        'PropertyID',
        'FundCd',
        'Reference',
        'ReferenceControlNo',
        'ReferenceDetailsID',
        'ReceiptQty',
        'IssuedQty',
        'BalanceQty',
        'Amount',
        'DateLog',
        'TimeLog',
        'PreparedBy',
        'AccountableOfficer',
        'Status'
    ];

    protected $casts = [
        'ReceiptQty' => 'decimal:2',
        'IssuedQty' => 'decimal:2',
        'BalanceQty' => 'decimal:2',
        'Amount' => 'decimal:2',
        'DateLog' => 'date',
        'TimeLog' => 'datetime'
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class, 'PropertyID');
    }

    public function preparedBy()
    {
        return $this->belongsTo(Employee::class, 'PreparedBy');
    }

    public function accountableOfficer()
    {
        return $this->belongsTo(Employee::class, 'AccountableOfficer');
    }

    // Scopes
    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('PropertyID', $propertyId);
    }

    public function scopeByReference($query, $reference)
    {
        return $query->where('Reference', $reference);
    }

    public function scopeByReferenceControlNo($query, $controlNo)
    {
        return $query->where('ReferenceControlNo', $controlNo);
    }

    public function scopeByFund($query, $fundCd)
    {
        return $query->where('FundCd', $fundCd);
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '!=', 'X');
    }

    // Methods for property card operations
    public static function insertPropertyCard($header)
    {
        // Delete existing entries
        self::where('Reference', $header['Reference'])
            ->where('ReferenceControlNo', $header['ReferenceControlNo'])
            ->where('FundCd', $header['fundcd'])
            ->where('PropertyID', $header['PropertyID'])
            ->delete();

        // Insert new entry
        return self::create([
            'PropertyID' => $header['PropertyID'],
            'FundCd' => $header['fundcd'],
            'Reference' => $header['Reference'],
            'ReferenceControlNo' => $header['ReferenceControlNo'],
            'ReferenceDetailsID' => $header['ReferenceDetailsID'],
            'ReceiptQty' => $header['ReceiptQty'],
            'IssuedQty' => $header['IssuedQty'],
            'BalanceQty' => $header['BalanceQty'],
            'Amount' => $header['UnitPrice'],
            'DateLog' => $header['date'],
            'TimeLog' => $header['time'],
            'PreparedBy' => $header['PreparedBy']
        ]);
    }

    public static function insertRISPropertyCard($header)
    {
        // Delete existing RIS entries
        self::where('Reference', 'RIS')
            ->where('ReferenceControlNo', $header['riscontrolno'])
            ->where('FundCd', $header['fundcd'])
            ->where('PropertyID', $header['PropertyID'])
            ->delete();

        // Insert new RIS entry
        return self::create([
            'PropertyID' => $header['PropertyID'],
            'FundCd' => $header['fundcd'],
            'Reference' => 'RIS',
            'ReferenceControlNo' => $header['riscontrolno'],
            'ReferenceDetailsID' => $header['ReferenceDetailsID'],
            'ReceiptQty' => $header['ReceiptQty'],
            'IssuedQty' => $header['IssuedQty'],
            'BalanceQty' => $header['BalanceQty'],
            'Amount' => $header['UnitPrice'],
            'DateLog' => $header['date'],
            'TimeLog' => $header['time'],
            'PreparedBy' => $header['PreparedBy']
        ]);
    }
}
