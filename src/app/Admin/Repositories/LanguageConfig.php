<?php

namespace App\Admin\Repositories;

use App\Models\LanguageConfig as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class LanguageConfig extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
