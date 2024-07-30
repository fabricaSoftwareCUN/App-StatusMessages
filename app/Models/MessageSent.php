<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where()
 */
class MessageSent extends Model
{
    use HasFactory;

    protected $table = 'ICEBERG.MSJ_ENVIADOS';
}
