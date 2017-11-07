<?php

namespace {{namespace}};

use Prettus\Repository\Eloquent\BaseRepository;
use {{modelNamespace}}\{{modelName}};

class {{name}} extends BaseRepository
{
    {{searchable}}

    public function model()
    {
        return {{modelName}}::class;
    }
}