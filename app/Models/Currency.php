<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string currency
 * @property float buy
 * @property float sell
 * @property string begins_at
 * @property string office_id
 */
class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency', 'buy', 'sell', 'begins_at', 'office_id'
    ];
}
