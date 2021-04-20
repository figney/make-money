<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


class LanguageConfig extends Model
{
    use HasDateTimeFormatter, Cachable;

    const CACHE_TAG = 'language_config';

    protected $table = 'language_config';

    protected $casts = [
        'content' => 'json',
    ];

    protected $guarded = [];


    public static function AllGroup(): array
    {
        return self::query()->groupBy('group')->select(['group'])->pluck('group', 'group')->toArray();
    }

    protected static function booted()
    {
       /* static::updated(function () {
            \Cache::tags(self::CACHE_TAG)->flush();
        });
        static::created(function () {
            \Cache::tags(self::CACHE_TAG)->flush();
        });*/
    }

}
