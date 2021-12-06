<?php

namespace App\Repository\Eloquent;

use App\Models\Developer;
use App\Repository\DeveloperRepositoryInterface;
use Illuminate\Support\Collection;

class DeveloperRepository extends BaseRepository implements DeveloperRepositoryInterface
{

    /**
     * UserRepository constructor.
     *
     * @param DeveloperRepository $model
     */
    public function __construct(Developer $model)
    {
        parent::__construct($model);
    }

}
