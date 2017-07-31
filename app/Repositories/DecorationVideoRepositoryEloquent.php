<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DecorationVideoRepository;
use App\Models\DecorationVideo;
use App\Validators\DecorationVideoValidator;

/**
 * Class DecorationVideoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class DecorationVideoRepositoryEloquent extends BaseRepository implements DecorationVideoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DecorationVideo::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
