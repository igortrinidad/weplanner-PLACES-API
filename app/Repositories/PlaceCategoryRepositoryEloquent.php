<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceCategoryRepository;
use App\Models\PlaceCategory;
use App\Validators\PlaceCategoryValidator;

/**
 * Class PlaceCategoryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceCategoryRepositoryEloquent extends BaseRepository implements PlaceCategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceCategory::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
