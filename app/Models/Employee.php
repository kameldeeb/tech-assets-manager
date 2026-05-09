<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    public function loans()
{
    return $this->hasMany(Loan::class);
}
public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function department()
{
    return $this->belongsTo(Department::class);
}
}
