<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
    'asset_type_id',
    'serial_number',
    'purchase_date',
    'status',
];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }
}
