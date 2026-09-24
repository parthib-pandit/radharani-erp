<?php
namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;

class GstRate extends Model
{
    public $timestamps = false;
    protected $fillable = ['category', 'rate_percent'];

    public static function forCategory(string $category): float
    {
        return static::where('category', $category)->value('rate_percent') ?? 3.0; // 3% default for gold jewellery
    }
}
