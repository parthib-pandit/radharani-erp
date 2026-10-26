<?php
namespace App\Models\Sales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/** A sale that was cancelled: who, why, and what happened to the money. Insert-only, one per sale. */
class SaleCancellation extends Model
{
    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime', 'was_verified' => 'boolean'];

    protected $fillable = ['sale_id', 'reason', 'was_verified', 'held_on_account', 'credit_returned', 'user_id', 'created_at'];

    protected static function booted()
    {
        static::creating(fn ($c) => $c->created_at ??= now());
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
