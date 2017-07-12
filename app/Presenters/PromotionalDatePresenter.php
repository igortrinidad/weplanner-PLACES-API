<?php

namespace App\Presenters;

use App\Transformers\PromotionalDateTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PromotionalDatePresenter
 *
 * @package namespace App\Presenters;
 */
class PromotionalDatePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PromotionalDateTransformer();
    }
}
