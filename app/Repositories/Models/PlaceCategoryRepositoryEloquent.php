<?php

namespace App\Repositories\Models;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Models\PlaceCategoryRepository;
use App\Models\Models\PlaceCategory;
use App\Validators\Models\PlaceCategoryValidator;

/**
 * Class PlaceCategoryRepositoryEloquent
 * @package namespace App\Repositories\Models;
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
