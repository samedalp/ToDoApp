<?php

namespace App\Repository\Eloquent;

use App\Models\Provider;
use App\Repository\ProviderRepositoryInterface;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{

    /**
     * ProviderRepository constructor.
     *
     * @param Provider $model
     */
    public function __construct(Provider $model)
    {
        parent::__construct($model);
    }



}
