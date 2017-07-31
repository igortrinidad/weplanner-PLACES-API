<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DecorationPhotoRepository;
use App\Models\DecorationPhoto;
use App\Validators\DecorationPhotoValidator;

/**
 * Class DecorationPhotoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class DecorationPhotoRepositoryEloquent extends BaseRepository implements DecorationPhotoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DecorationPhoto::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
