<?php
namespace App\Models\Movement;

use Illuminate\Database\Eloquent\Model;

class RateLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['metal', 'rate', 'source', 'updated_by', 'created_at'];

    protected static function booted()
    {
        static::creating(fn ($log) => $log->created_at ??= now());
    }

    public static function latestFor(string $metal): ?self
    {
        return static::where('metal', $metal)->latest('created_at')->first();
    }
}
