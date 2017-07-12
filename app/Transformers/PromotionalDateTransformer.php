<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PromotionalDate;

/**
 * Class PromotionalDateTransformer
 * @package namespace App\Transformers;
 */
class PromotionalDateTransformer extends TransformerAbstract
{

    /**
     * Transform the \PromotionalDate entity
     * @param \PromotionalDate $model
     *
     * @return array
     */
    public function transform(PromotionalDate $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
