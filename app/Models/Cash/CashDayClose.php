<?php
namespace App\Models\Cash;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/** A day closed on one book: what the book said, what was counted by denomination, and the difference. Insert-only. */
class CashDayClose extends Model
{
    public $timestamps = false;

    protected $casts = ['closed_on' => 'date', 'created_at' => 'datetime', 'denominations' => 'array', 'expected' => 'float', 'counted' => 'float', 'difference' => 'float'];

    protected $fillable = ['book', 'closed_on', 'expected', 'counted', 'difference', 'denominations', 'note', 'user_id', 'created_at'];

    protected static function booted()
    {
        static::creating(fn ($c) => $c->created_at ??= now());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
