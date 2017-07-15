<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PromotionalDateRepository;
use App\Models\PromotionalDate;
use App\Validators\PromotionalDateValidator;

/**
 * Class PromotionalDateRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PromotionalDateRepositoryEloquent extends BaseRepository implements PromotionalDateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PromotionalDate::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PromotionalDateValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
