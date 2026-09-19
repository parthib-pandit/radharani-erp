<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'designation', 'salary', 'joining_date', 'status'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
