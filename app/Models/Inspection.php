<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    //
    public function asset()
{
    return $this->belongsTo(Asset::class);
}

public function loan()
{
    return $this->belongsTo(Loan::class);
}

public function inspector()
{
    return $this->belongsTo(User::class, 'inspected_by');
}
}
