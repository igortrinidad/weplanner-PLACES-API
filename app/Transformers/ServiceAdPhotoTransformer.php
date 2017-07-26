<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ServiceAdPhoto;

/**
 * Class ServiceAdPhotoTransformer
 * @package namespace App\Transformers;
 */
class ServiceAdPhotoTransformer extends TransformerAbstract
{

    /**
     * Transform the \ServiceAdPhoto entity
     * @param \ServiceAdPhoto $model
     *
     * @return array
     */
    public function transform(ServiceAdPhoto $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
