<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'borrowed_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * Get the user who borrowed the item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Determine if the loan is active (item is currently borrowed)
     */
    public function isActive(): bool
    {
        return $this->returned_at === null;
    }

    /**
     * Determine if the loan is completed (item has been returned)
     */
    public function isReturned(): bool
    {
        return $this->returned_at !== null;
    }

    /**
     * Scope a query to only include active loans
     */
    public function scopeActive($query)
    {
        return $query->whereNull('returned_at');
    }

    /**
     * Scope a query to only include returned loans
     */
    public function scopeReturned($query)
    {
        return $query->whereNotNull('returned_at');
    }

    /**
     * Scope a query to only include overdue loans
     */
    public function scopeOverdue($query)
    {
        return $query->whereNull('returned_at')
            ->where('borrowed_at', '<=', now()->subDays(config('locker.loan_period', 14)));
    }
}
