<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hystory extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity',
        'entity_id',
        'data',
        'entity_updated_at'
    ];
}
