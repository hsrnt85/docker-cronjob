<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountType extends Model
{
    use HasFactory;

    protected $table = 'bank_account_type';
    protected $primaryKey = 'id';
    protected $fillable = ['bank_account_type'];
    public $timestamps = false;

    public function bankAccount()
    {
        return $this->hasMany(BankAccount::class, 'bank_account_type');
    }
    
}
