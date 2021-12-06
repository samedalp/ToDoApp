<?php

namespace App\Repository\Eloquent;

use App\Logger\LoggerInterface;
use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $attributes
     * @param array $with
     * @param string[] $columns
     */
    public function findByAttributes(array $attributes,$orderBy = null, $with = [], $columns = array("*")): mixed
    {
        $query = $this->queryBuilder($attributes,$orderBy);

        if (!empty($with)) {
            foreach ($with as $relation) {
                $query = $query->with($relation);
            }
        }

        try {
            return $query->first($columns);
        } catch (\Exception $exception) {
            dd($exception);
            return false;
        }
    }

    /**
     * @param array $attributes
     * @param null $orderBy
     * @param string $sortOrder
     * @param array $with
     */
    protected function queryBuilder(array $attributes, $orderBy = null, $sortOrder = 'asc', $with = [])
    {

        $query = $this->model->query();

        foreach ($attributes as $field => $value) {
            if ($field == 'where_in') {
                foreach ($value as $col => $item) {
                    $query = $query->whereIn($col, $item);
                }
            } else if ($field == 'whereJsonContains') {
                foreach ($value as $col => $json) {
                    $query = $query->whereJsonContains($col, $json);
                }
            } else if ($field == 'limit') {
                $query = $query->limit($value);
            } else if ($field == 'offset') {
                $query = $query->offset($value);
            } else if ($field == 'doesntHave') {
                foreach ($value as $doesntHave) {
                    $query = $query->doesntHave($doesntHave);
                }
            } else if ($field == 'whereDoesntHave') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $like) {
                        $query = $query->whereDoesntHave($first, function ($q) use ($col, $like) {
                            $q->where($col, $like);
                        });
                    }
                }
            } else if ($field == 'whereHas') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $like) {
                        $query = $query->whereHas($first, function ($q) use ($col, $like) {
                            $q->where($col, $like);
                        });
                    }
                }
            } else if ($field == 'whereHasNot') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $not) {
                        $query = $query->whereHas($first, function ($q) use ($col, $not) {
                            $q->where($col, "!=", $not);
                        });
                    }
                }
            } else if ($field == 'whereHasLike') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $like) {
                        $query = $query->whereHas($first, function ($q) use ($col, $like) {
                            $q->where($col, 'LIKE', '%' . $like . '%');
                        });
                    }
                }
            } else if ($field == 'whereHasIn') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $in) {
                        $query = $query->whereHas($first, function ($q) use ($col, $in) {
                            $q->whereIn($col, $in);
                        });
                    }
                }
            } else if ($field == 'whereHasBetween') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $in) {
                        $query = $query->whereHas($first, function ($q) use ($col, $in) {
                            $q->whereBetween($col, $in);
                        });
                    }
                }
            } else if ($field == 'like') {
                foreach ($value as $col => $like) {
                    $query = $query->where($col, 'LIKE', $like);
                }
            } else if ($field == 'not') {
                foreach ($value as $col => $not) {
                    $query = $query->where($col, '!=', $not);
                }
            } else if ($field == 'between') {
                $query = $query->whereBetween(key($value), $value[key($value)]);
            } else if ($field == 'orWhereHas') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $in) {
                        $query = $query->orWhereHas($first, function ($q) use ($col, $in) {
                            $q->where($col, $in);
                        });
                    }
                }
            } else if ($field == 'orWhereHasIn') {
                foreach ($value as $first => $relations) {
                    foreach ($relations as $col => $in) {
                        $query = $query->orWhereHas($first, function ($q) use ($col, $in) {
                            $q->whereIn($col, $in);
                        });
                    }
                }
            } else if ($field == 'greater') {
                foreach ($value as $col => $item) {
                    $query = $query->where($col, '>', $item);
                }
            } else if ($field == 'less') {
                foreach ($value as $col => $item) {
                    $query = $query->where($col, '<', $item);
                }
            } else if ($field == 'orWhere') {
                foreach ($value as $col => $array) {
                    $query = $query->where(function ($query) use ($col, $array) {
                        $query->where($col, Arr::first($array))->orWhere($col, Arr::last($array));
                    });
                }
            } else if ($field != 'childRepositoryAttribute') {
                $query = $query->where($field, $value);
            }
        }

        if (null !== $orderBy && !isset($orderBy["has"])) {
            if (!is_array($orderBy)) {
                $orderBy = [$orderBy];
            }

            foreach ($orderBy as $order) {
                $query->orderBy($order, $sortOrder);
            }
        }

        if (!empty($with)) {
            foreach ($with as $relation) {
                $query = $query->with($relation);
            }
        }

        return $query;
    }

    /**
     * @param array $attributes
     * @param array $with
     * @param array $columns
     * @return mixed
     */
    public function getManyByAttributeCounts(array $attributes, array $with = [], array $columns = array("*")) : mixed
    {
        $query = $this->queryBuilder($attributes);

        if (!empty($with)) {
            foreach ($with as $relation) {
                $query = $query->with($relation);
            }
        }

        try {
            return $query->count($columns);

        } catch (\Exception $exception) {
            return false;
        }
    }


    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*')): mixed
    {
        try {
            return $this->model->get($columns);
        } catch (\Exception $exception) {
            return false;
        }
    }


    /**
     * @param array $dataArray
     *
     * @return mixed
     */
    public function addMany(array $dataArray): mixed
    {
        try {
            $result = [];
            foreach ($dataArray as $data) {
                $createdItem = $this->model->create($data);
                $result[$createdItem->id] = $data;
            }
            return $result;
        } catch (\Exception $exception) {
        }
    }


    /**
     * @param array $attributes
     * @param null $orderBy
     * @param string $sortOrder
     * @param array $with
     * @param array $columns
     * @return mixed
     */
    public function getManyByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc', $with = [], $columns = array("*")): mixed
    {
        $query = $this->queryBuilder($attributes, $orderBy, $sortOrder, $with);

        try {
            if (isset($orderBy["has"])) {
                if ($sortOrder == "asc") {
                    return $query->get($columns)->sortBy(function ($query) use ($orderBy) {
                        return $query->{key($orderBy["has"])}->{head($orderBy["has"])};
                    });
                }

                return $query->get($columns)->sortByDesc(function ($query) use ($orderBy) {
                    return $query->{key($orderBy["has"])}->{head($orderBy["has"])};
                });
            }

            return $query->get($columns);

        } catch (\Exception $exception) {
            return false;
        }
    }
}
