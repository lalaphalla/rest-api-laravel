<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Factories\Factory hasInvoices(int $count = 1, ?callable $callback = null)
 */
class Customer extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'type',
        'address',
        'city',
        'state',
        'postal_code',
    ];
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
