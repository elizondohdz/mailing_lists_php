<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Contact extends Model
{
    protected $fillable = ["fullname", "phone", "email", "uuid"];
    
    use HasFactory;

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
}
