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

/**
 * Class Attributes
 *
 * @package Kemana\Labelmanager\Model
 */
class Attributes extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Catalog\Helper\Product\Edit\Action\Attribute
     */
    protected $_attributeAction;
    protected $_eavSetupFactory;
    protected $_attributeFactory;

    /**
     * Attributes constructor.
     * @param \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeAction
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory
     */
    public function __construct(
        \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeAction,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory
    ) {
        $this->_attributeAction = $attributeAction;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_attributeFactory = $attributeFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        //$attribOptions = $this->_attributeAction->getAttributes()->getData();
        $attribOptions = $this->_attributeAction->getAttributes()
                    ->addFieldToFilter('frontend_input', ['eq'=>'boolean'])->getData();
        /*$attribOptions->getSelect()->joinLeft(
                array('catEavAtt'=>'catalog_eav_attribute'),
                'catEavAtt.attribute_id = main_table.attribute_id',array('*'))
            ->where("main_table.frontend_input='boolean' AND catEavAtt.used_in_product_listing = 1");*/
        $options = [];
        $options[''] = __('-- Please Select --');
        foreach ($attribOptions as $attribs) {
            $attributeCode = $attribs['attribute_code'];
            $attributeLabel = $attribs['frontend_label'];
            $options[$attributeCode] = $attributeLabel;
        }
        return $options;
    }
}
