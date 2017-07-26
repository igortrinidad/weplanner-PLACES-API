<?php

namespace App\Presenters;

use App\Transformers\ServiceAdPhotoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ServiceAdPhotoPresenter
 *
 * @package namespace App\Presenters;
 */
class ServiceAdPhotoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ServiceAdPhotoTransformer();
    }
}
