<?php
namespace App\Models\Cash;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/** One line of a cash book: money in or out, with a reason and who wrote it. Insert-only; a mistake is reversed by a new row. */
class CashEntry extends Model
{
    public const BOOKS = ['main' => 'Main cash (vault)', 'petty' => 'Petty cash'];

    public $timestamps = false;

    protected $casts = ['occurred_on' => 'date', 'created_at' => 'datetime', 'amount' => 'float'];

    protected $fillable = ['book', 'kind', 'amount', 'occurred_on', 'reason', 'note', 'transfer_id', 'reverses_id', 'user_id', 'created_at'];

    protected static function booted()
    {
        static::creating(fn ($e) => $e->created_at ??= now());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** The row that put this one right, if any. */
    public function reversal()
    {
        return $this->hasOne(self::class, 'reverses_id');
    }

    /** What it does to the balance: + for money in (an opening balance counts as money in), - for money out. */
    public function getSignedAttribute(): float
    {
        return $this->kind === 'payment' ? -$this->amount : $this->amount;
    }
}
