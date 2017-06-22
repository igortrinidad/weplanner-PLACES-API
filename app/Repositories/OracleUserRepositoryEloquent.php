<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\OracleUserRepository;
use App\Models\OracleUser;
use App\Validators\OracleUserValidator;

/**
 * Class OracleUserRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OracleUserRepositoryEloquent extends BaseRepository implements OracleUserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OracleUser::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return OracleUserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
