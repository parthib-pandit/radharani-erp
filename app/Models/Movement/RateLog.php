<?php
namespace App\Models\Movement;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RateLog extends Model
{
    public $timestamps = false;
    // $timestamps=false also disables Eloquent's default created_at
    // date-casting, so it needs an explicit cast or ->format() calls on it
    // (RateHistoryLog) fatal-error on a raw string.
    protected $casts = ['created_at' => 'datetime'];
    protected $fillable = ['metal', 'rate', 'source', 'updated_by', 'created_at'];

    protected static function booted()
    {
        static::creating(fn ($log) => $log->created_at ??= now());
    }

    public static function latestFor(string $metal): ?self
    {
        return static::where('metal', $metal)->latest('created_at')->first();
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
