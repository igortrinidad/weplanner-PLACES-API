<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AdTrackingRepository;
use App\Models\AdTracking;
use App\Validators\AdTrackingValidator;

/**
 * Class AdTrackingRepositoryEloquent
 * @package namespace App\Repositories;
 */
class AdTrackingRepositoryEloquent extends BaseRepository implements AdTrackingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AdTracking::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
