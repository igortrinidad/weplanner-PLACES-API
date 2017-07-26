<?php

namespace App\Presenters;

use App\Transformers\AdvertiserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AdvertiserPresenter
 *
 * @package namespace App\Presenters;
 */
class AdvertiserPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AdvertiserTransformer();
    }
}
