<?php
namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * An internal code handed out on a downloaded import sheet, kept for the piece that will carry it. Written once when
 * the sheet is downloaded and marked used once when the piece is imported.
 */
class ReservedCode extends Model
{
    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime', 'used_at' => 'datetime'];

    protected $fillable = ['code', 'user_id', 'created_at', 'used_item_id', 'used_at'];

    /**
     * Reserves $count new codes, from the same non-ambiguous alphabet as every internal code, none of them in use on a
     * piece (deleted ones included) or already reserved.
     *
     * @return Collection<int, string>
     */
    public static function reserve(int $count): Collection
    {
        return DB::transaction(function () use ($count) {
            $codes = collect();
            while ($codes->count() < $count) {
                $code = '';
                for ($i = 0; $i < 5; $i++) {
                    $code .= Item::CODE_ALPHABET[random_int(0, strlen(Item::CODE_ALPHABET) - 1)];
                }
                if ($codes->contains($code) || Item::withTrashed()->where('internal_code', $code)->exists() || static::where('code', $code)->exists()) {
                    continue;
                }
                static::create(['code' => $code, 'user_id' => Auth::id()]);
                $codes->push($code);
            }

            return $codes;
        });
    }

    public static function isTaken(string $code): bool
    {
        return static::where('code', strtoupper($code))->exists();
    }

    /** Mark a reserved code as carried by this piece. A code that was never reserved is left alone. */
    public static function claim(string $code, Item $item): void
    {
        static::where('code', strtoupper($code))->whereNull('used_item_id')->update(['used_item_id' => $item->id, 'used_at' => now()]);
    }
}
