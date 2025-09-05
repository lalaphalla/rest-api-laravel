<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Factories\Factory hasInvoices(int $count = 1, ?callable $callback = null)
 */
class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    /**
     * 
     *
     * @method static \Illuminate\Database\Eloquent\Factories\Factory hasInvoices(int $count = 1, ?callable $callback = null)
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
