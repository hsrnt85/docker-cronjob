<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank';
    protected $primaryKey = 'id';
    protected $fillable = ['bank_name'];
    public $timestamps = false;

    public function bankAccount()
    {
        return $this->hasMany(BankAccount::class, 'bank_id');
    }
}
