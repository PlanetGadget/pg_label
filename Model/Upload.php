<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model;

use Magento\Framework\File\Uploader;

/**
 * Class Upload
 *
 * @package Kemana\Labelmanager\Model
 */
class Upload
{
    /**
     * uploader factory
     *
     * @var \Magento\Core\Model\File\UploaderFactory
     */
    protected $uploadFactory;

    /**
     * constructor
     *
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(\Magento\Framework\Image\AdapterFactory $imageFactory)
    {
        $this->imageFactory = $imageFactory;
    }

    /**
     * @param $productLabelSourceImg
     * @return mixed
     * @throws \Exception
     */
    public function resizeProdImage($productLabelSourceImg)
    {
        //#NP2-2298
        $image = $this->imageFactory->create();
        $image->open(BP . '/pub/media/' . $productLabelSourceImg);
        $image->keepAspectRatio(true);
        // $image->resize($prodLabelWidth, $prodLabelHeight);
        $productLabelImg = $productLabelSourceImg;
        $image->save(BP . '/pub/media/labels/' . $productLabelImg);

        return $productLabelImg;
    }
}
