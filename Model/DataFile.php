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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Setup\Exception;

class DataFile extends \Magento\Framework\DataObject
{
    const FILE_PATH_PREFIX = 'labelmanager/general/';
    /**
     * File Name Prefix
     */
    const FILE_NAME_PREFIX = 'labelmanager_';

    /**
     * File storing directory
     */
    const DIR_NAME = 'labelmanager';

    /**
     * @var \Magento\Framework\Filesystem
     */

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LabelList
     */
    protected $labelList;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $storeManager;

    /**
     * @var Working directory, a string
     */
    protected $workingDirectory;

    protected $configWriter;

    protected $labelFileFactory;

    protected $labelFileCollectionFactory;

    protected $file;

    /**
     * DataFile constructor.
     * @param \Magento\Framework\Filesystem $filesystem
     * @param LabelList $labelList
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollection
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param LabelFileFactory $labelFileFactory
     * @param ResourceModel\LabelFile\CollectionFactory $labelFileCollectionFactory
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        LabelList $labelList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollection,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Kemana\Labelmanager\Model\LabelFileFactory $labelFileFactory,
        \Kemana\Labelmanager\Model\ResourceModel\LabelFile\CollectionFactory $labelFileCollectionFactory,
        \Magento\Framework\Filesystem\Driver\File $file,
        array $data = []
    ) {
        $this->fileSystem = $filesystem;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->labelList = $labelList;
        $this->websiteCollection = $websiteCollection;
        $this->labelFileFactory = $labelFileFactory;
        $this->labelFileCollectionFactory = $labelFileCollectionFactory;
        $this->file = $file;
        parent::__construct($data);
    }

    /**
     * Temp file path
     * @return string
     */
    protected function getTmpFilePath()
    {
        return uniqid(self::FILE_NAME_PREFIX) . '_tmp.json';
    }

    /**
     * File Path
     * @return string
     */
    public function getFilePath($websiteCode)
    {
        return self::DIR_NAME . '/' . uniqid(self::FILE_NAME_PREFIX) . '_' . $websiteCode . '.json';
    }

    /**
     * @param $filePath
     * @param $websiteCode
     * @return mixed
     * @throws Exception
     */
    public function saveFilePath($filePath, $websiteCode)
    {
        $labelFiles = $this->labelFileCollectionFactory->create()
            ->addFieldToFilter('website', $websiteCode);
        if ($labelFiles->getSize()) {
            $labelFile = $labelFiles->getFirstItem();
        } else {
            $labelFile = $this->labelFileFactory->create();
        }
        $oldPath = $labelFile->getData('path');
        $labelFile->setData('path', $filePath);
        $labelFile->setData('website', $websiteCode);
        try {
            $labelFile->save();
            return $oldPath;
        } catch (\Exception $exception) {
            throw new Exception(__('Label saving failed. Error :' . $exception->getMessage()));
        }
    }

    /**
     * Preparing file
     *
     * @param $websiteId
     * @return bool
     * @throws LocalizedException
     */
    public function prepareFile($websiteId)
    {
        try {
            $fileData = [];
            $this->getTmpFilePath();
            $data = $this->labelList->prepareLabelDataJson($websiteId);
            $decordedData = json_decode($data);
            $jsonData = \Zend_Json::encode($data);
            $tmpFile = $this->getTmpFilePath();
            $website = $this->websiteCollection->create()->getItemById($websiteId);
            $websiteCode = $website->getCode();
            $this->workingDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::TMP);
            $destination = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
            $this->workingDirectory->writeFile($tmpFile, $jsonData);
            /** writing file path to core config table */
            $filePath = $this->getFilePath($websiteCode);
            $existing = $this->saveFilePath($filePath, $websiteCode);
            /** deleting existing file for website. */
            if ($existing && file_exists($destination->getAbsolutePath() . $existing)) {
                $this->file->deleteFile($destination->getAbsolutePath() . $existing);
            }
            $this->workingDirectory->copyFile($tmpFile, $filePath, $destination);
            $this->workingDirectory->delete($tmpFile);

            if ($destination->isExist($filePath)) {
                $fileData = [
                    'file_exists' => 'true',
                    'labels' => $decordedData->labels,
                    'labelProducts' => $decordedData->labelProducts
                ];
            } else {
                $fileData = [
                    'file_exists' => 'false',
                    'labels' => $decordedData->labels,
                    'labelProducts' => $decordedData->labelProducts
                ];
            }
            return $fileData;
        } catch (FileSystemException $ex) {
            throw new LocalizedException(__('The json file creation process failed. Error : ' . $ex->getMessage()));
        } catch (\Exception $e) {
            throw new LocalizedException(__('Process failed. Error : ' . $e->getMessage()));
        }
    }

    /**
     * Retrieving list of available files.
     *
     * @return array
     * @throws FileSystemException
     */
    public function getLabelDataFiles()
    {
        $files = [];
        $mediaDir = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        foreach ($this->storeManager->getWebsites() as $website) {
            $websiteCode = $website->getCode();
            $websiteId = $website->getId();
            if (!isset($files[$websiteId])) {
                $file = $this->getFilePathByWebsite($websiteCode);
                $files[$websiteId] = [
                    'file' => $file,
                    'is_file' => $mediaDir->isExist($file),
                    'website' => $websiteCode
                ];
            }
        }
        return $files;
    }

    /**
     * @param $websiteCode
     * @return string
     */
    public function getFilePathByWebsite($websiteCode)
    {
        $labelFiles = $this->labelFileCollectionFactory->create()
            ->addFieldToFilter('website', $websiteCode);
        $file = '';
        if ($labelFile = $labelFiles->getFirstItem()) {
            $file = $labelFile->getData('path');
        }
        return $file;
    }
}
