<?php

namespace App\Presenters;

use App\Transformers\ServiceAdTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ServiceAdPresenter
 *
 * @package namespace App\Presenters;
 */
class ServiceAdPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ServiceAdTransformer();
    }
}
