<?php
namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    protected $fillable = ['scheme_id', 'amount', 'paid_on'];

    public function scheme()
    {
        return $this->belongsTo(InstallmentScheme::class, 'scheme_id');
    }
}
