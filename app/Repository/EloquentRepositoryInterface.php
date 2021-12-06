<?php


namespace App\Repository;


use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model;


    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*')): mixed;


    /**
     * @param array $attributes
     * @param array $with
     * @param string[] $columns
     * @return mixed
     */
    public function getManyByAttributeCounts(array $attributes, array $with = [], array $columns = array("*")): mixed;


    /**
     * @param array $attributes
     * @param array $with
     * @param array $columns
     * @return mixed
     */
    public function findByAttributes(array $attributes, $with = [], $columns = array("*")): mixed;

    /**
     * @param array $data
     * @return mixed
     */
    public function addMany(array $data): mixed;


}
