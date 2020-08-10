<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\ViewModel;

use Kemana\Labelmanager\Model\DataFile;

class FileManager implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Kemana\Labelmanager\Model\DataFile
     */
    private $fileModel;

    /**
     * @var \Magento\Directory\Model\Currency
     */
    private $currency;

    /**
     * @var \Magento\Directory\Model\Currency
     */
    private $currencyFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * FileManager constructor.
     * @param \Kemana\Labelmanager\Model\DataFile $fileModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\Currency $currency
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        DataFile $fileModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->storeManager = $storeManager;
        $this->fileModel = $fileModel;
        $this->currency = $currency;
        $this->productFactory = $productFactory;
        $this->currencyFactory = $currencyFactory->create();
    }

    /**
     * @return bool|string
     */
    public function getLabelDataFile()
    {
        try {
            $website = $this->storeManager->getWebsite()->getCode();
            $path = $this->fileModel->getFilePathByWebsite($website);
            return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencySymbol()
    {
        $baseCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $currentCurrency = $this->currencyFactory->load($baseCode);
        return $currentCurrency->getCurrencySymbol();
    }

    /**
     * @param $productId
     * @return array|string
     */
    public function getProductType($productId)
    {
        return $this->productFactory->create()->load($productId)->getTypeId();
    }
}
