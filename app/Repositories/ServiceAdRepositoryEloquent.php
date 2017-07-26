<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ServiceAdRepository;
use App\Models\ServiceAd;
use App\Validators\ServiceAdValidator;

/**
 * Class ServiceAdRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ServiceAdRepositoryEloquent extends BaseRepository implements ServiceAdRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ServiceAd::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ServiceAdValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
