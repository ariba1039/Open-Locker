<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @uses \Database\Factories\ItemLoanFactory
 */
class ItemLoan extends Model
{
    /** @use HasFactory<\Database\Factories\ItemLoanFactory> */
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
     *
     * @return BelongsTo<User, ItemLoan>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed item
     *
     * @return BelongsTo<Item, ItemLoan>
     */
    public function item(): BelongsTo
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
     *
     * @param  Builder<ItemLoan>  $query
     * @return Builder<ItemLoan>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('returned_at');
    }

    /**
     * Scope a query to only include returned loans
     *
     * @param  Builder<ItemLoan>  $query
     * @return Builder<ItemLoan>
     */
    public function scopeReturned(Builder $query): Builder
    {
        return $query->whereNotNull('returned_at');
    }

    /**
     * Scope a query to only include overdue loans
     *
     * @param  Builder<ItemLoan>  $query
     * @return Builder<ItemLoan>
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNull('returned_at')
            ->where('borrowed_at', '<=', now()->subDays(config('locker.loan_period', 14)));
    }
}
