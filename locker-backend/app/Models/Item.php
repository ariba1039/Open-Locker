<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'locker_id',
    ];

    /**
     * Get all loans for this item
     */
    public function loans(): HasMany
    {
        return $this->hasMany(ItemLoan::class);
    }

    /**
     * Get the current active loan for this item
     */
    public function activeLoan(): HasOne
    {
        return $this->hasOne(ItemLoan::class)->whereNull('returned_at');
    }

    /**
     * Get the current borrower of the item through the active loan
     */
    public function currentBorrower(): HasOne
    {
        return $this->hasOneThrough(
            User::class,
            ItemLoan::class,
            'item_id', // Foreign key on item_loans table
            'id', // Foreign key on users table
            'id', // Local key on items table
            'user_id' // Local key on item_loans table
        )->whereNull('item_loans.returned_at');
    }
}
