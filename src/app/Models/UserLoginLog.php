<?php


namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class UserLoginLog extends Model
{


    protected $connection = "mongodb";

    protected $guarded = [];

}
