<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Day extends Model
{
    use HasUuids;

    protected $table = 'days';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'order',
    ];
}
