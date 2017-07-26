<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ServiceAd;

/**
 * Class ServiceAdTransformer
 * @package namespace App\Transformers;
 */
class ServiceAdTransformer extends TransformerAbstract
{

    /**
     * Transform the \ServiceAd entity
     * @param \ServiceAd $model
     *
     * @return array
     */
    public function transform(ServiceAd $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
