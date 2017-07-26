<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ServiceAdPhotoRepository;
use App\Models\ServiceAdPhoto;
use App\Validators\ServiceAdPhotoValidator;

/**
 * Class ServiceAdPhotoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ServiceAdPhotoRepositoryEloquent extends BaseRepository implements ServiceAdPhotoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ServiceAdPhoto::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ServiceAdPhotoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
