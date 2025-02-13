<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;
    public function borrower(): HasOne
    {
        return $this->hasOne(User::class,"id","borrower_id");
    }

}
