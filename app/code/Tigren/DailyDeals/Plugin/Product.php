<?php

namespace Tigren\DailyDeals\Plugin;

use Magento\Framework\View\Element\Template;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
use \Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Product extends Template
{
    protected $dealCollection;
    protected $registry;
    protected $collectionItem;
    protected $_scopeConfig;

    public function __construct(
        Template\Context $context,
        DealCollection $dealCollection,
        \Magento\Framework\Registry $registry,
        ProductRepository $collection,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->dealCollection = $dealCollection;
        $this->registry = $registry;
        $this->collection = $collection;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        $config = $this->_scopeConfig->getValue('dailydeals_section_id/general/enable');
        $deal = $this->dealCollection->create();
        $dealSku = [];
        foreach ($deal as $dealItem) {
            $dealSku[] = $dealItem->getData('product_sku');
        }

        $id = $subject->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $parentId = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($id);

        if (isset($parentId[0])) {
            $productSku = $this->collection->getById($parentId[0])->getSku();
        } else {
            $productSku = $subject->getSku();
        }
//        var_dump($productQty); exit;
        if (in_array($productSku, $dealSku)) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $endTime = $deal->getItemByColumnValue('product_sku', $productSku)->getData('end_date_time');
            $startTime = $deal->getItemByColumnValue('product_sku', $productSku)->getData('start_date_time');
            $now = date('Y-m-d H:i:s');
            $status = $deal->getItemByColumnValue('product_sku', $productSku)->getData('status');
//            var_dump($now <= $endTime);
            if ($now <= $endTime && $status == 1 && $now >= $startTime && $config != 0) {
                return $result = $deal->getItemByColumnValue('product_sku', $productSku)->getData('deal_price');
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }
}
