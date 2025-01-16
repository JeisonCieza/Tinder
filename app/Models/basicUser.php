<?php

namespace App\Models;

use App\Enums\BasicGroupEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class basicUser extends Model
{
    /** @use HasFactory<\Database\Factories\BasicUserFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts=[
        'group'=>BasicGroupEnum::class
    ];

    function users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'basic_user');
    }
}
