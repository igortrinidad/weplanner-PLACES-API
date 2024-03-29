<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AdvertiserRepository;
use App\Models\Advertiser;
use App\Validators\AdvertiserValidator;

/**
 * Class AdvertiserRepositoryEloquent
 * @package namespace App\Repositories;
 */
class AdvertiserRepositoryEloquent extends BaseRepository implements AdvertiserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Advertiser::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AdvertiserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function create(array $attributes)
    {
        if(array_key_exists('password', $attributes)){
            $attributes['password'] = bcrypt($attributes['password']);
        }
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        if(array_key_exists('password', $attributes)){
            $attributes['password'] = bcrypt($attributes['password']);
        }

        return parent::update($attributes, $id);
    }
}
