<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Advertiser;

/**
 * Class AdvertiserTransformer
 * @package namespace App\Transformers;
 */
class AdvertiserTransformer extends TransformerAbstract
{

    /**
     * Transform the \Advertiser entity
     * @param \Advertiser $model
     *
     * @return array
     */
    public function transform(Advertiser $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
