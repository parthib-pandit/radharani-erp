<?php
namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name', 'type', 'phone', 'address', 'balance'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
