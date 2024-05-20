<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Redirect extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted(): void
    {
        parent::booted();

        self::creating(function (Redirect $redirect) {
            if($redirect->hash) return;

            $hash = Str::random(6);

            while (Redirect::query()->where('hash', $hash)->exists()) {
                $hash = Str::random(6);
            }

            $redirect->hash = $hash;
        });
    }
}
