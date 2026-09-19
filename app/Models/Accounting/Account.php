<?php
namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'type'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
