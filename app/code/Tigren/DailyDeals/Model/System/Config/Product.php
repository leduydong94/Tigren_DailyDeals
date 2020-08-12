<?php

namespace Tigren\DailyDeals\Model\System\Config;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ProductRepository;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealsCollection;

class Product implements ArrayInterface
{
    protected $options;
    protected $_respository;
    protected $_productFactory;
    protected $_dealFactory;

    public function __construct(
        ProductCollection $productFactory,
        ProductRepository $productRepository,
        DealsCollection $dealFactory
    )
    {
        $this->_productFactory = $productFactory;
        $this->_respository = $productRepository;
        $this->_dealFactory = $dealFactory;
    }

    public function getProductBySku($sku)
    {
        return $this->_respository->get($sku);
    }

    public function toOptionArray()
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $url = $urlInterface->getCurrentUrl();
        $products = $this->_productFactory->create();
        $deals = $this->_dealFactory->create();
        $dealSku = [];
        foreach ($deals as $deal) {
            $dealSku[] = $deal->getData('product_sku');
        }
        $options = [];
        if ($products->getSize()) {
            foreach ($products as $product) {
                $options = array_merge($options, [
                    $product->getData('sku') => __($this->getProductBySku($product->getData('sku'))->getName())
                ]);
                if (in_array($product->getData('sku'), $dealSku) && strpos($url, 'daily/deals/create') !== false) {
                    unset($options[$product->getData('sku')]);
                }
            }
        }
        return $options;
    }
}
