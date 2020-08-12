<?php

namespace Tigren\DailyDeals\Block\Adminhtml\Deals\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;

// use Magento\Cms\Model\Wysiwyg\Config;
use Tigren\DailyDeals\Model\System\Config\Status;
use Tigren\DailyDeals\Model\System\Config\Product;
//use Tigren\DailyDeals\Model\System\Config\Store;
use Magento\Store\Model\System\Store;

class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
// protected $_wysiwygConfig;

    /**
     * @var \Magetop\Helloworld\Model\System\Config\Status
     */
    protected $_status;
    protected $_product;
//    protected $_store;
    protected $_systemStore;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $status
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
// Config $wysiwygConfig,
        Status $status,
        Product $product,
//        Store $store,
        Store $systemStore,
        array $data = []
    )
    {
// $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;
        $this->_product = $product;
//        $this->_store = $store;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        /** @var $model \Magetop\Helloworld\Model\PostsFactory */
        $model = $this->_coreRegistry->registry('tigren_daily_deals');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
// new filed

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id']
            );
        }
        $fieldset->addField(
            'product_sku',
            'select',
            [
                'name' => 'product_sku',
                'label' => __('Product'),
                'options' => $this->_product->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'options' => $this->_status->toOptionArray()
            ]
        );
        $fieldset->addField(
            'store_view',
            'multiselect',
            [
                'name'     => 'store_view',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );
        $fieldset->addField(
            'deal_price',
            'text',
            [
                'name'      => 'deal_price',
                'label'     => __('Deal Price'),
                'title' => __('Deal Price'),
                'required' => true,
                'class' => 'validate-number'
            ]
        );
        $fieldset->addField(
            'deal_qty',
            'text',
            [
                'name'      => 'deal_qty',
                'label'     => __('Deal Quantity'),
                'title' => __('Deal Quantity'),
                'required' => true,
                'class' => 'validate-number'
            ]
        );
        $fieldset->addField(
            'start_date_time',
            'date',
            [
                'name' => 'start_date_time',
                'label' => __('Start On'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss'
            ]
        );
        $fieldset->addField(
            'end_date_time',
            'date',
            [
                'name' => 'end_date_time',
                'label' => __('End On'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss'
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Posts Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Posts Info');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
