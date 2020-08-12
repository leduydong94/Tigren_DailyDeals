<?php

namespace Tigren\DailyDeals\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Product extends \Magento\Store\Ui\Component\Listing\Column\Store
{
    protected $productRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        SystemStore $systemStore,
        Escaper $escaper,
        ProductRepositoryInterface $productRepository,
        array $components = [],
        array $data = [],
        $storeKey = 'store_id')
    {
        $this->productRepository = $productRepository;
        parent::__construct($context, $uiComponentFactory, $systemStore, $escaper, $components, $data, $storeKey);
    }


public function prepareDataSource(array $dataSource)
{
    if (isset($dataSource['data']['items'])) {
        foreach ($dataSource['data']['items'] as &$item) {
//            print_r($this->getData('name')); die;
            $product = $this->productRepository->get($item['product_sku']);
//            print_r($product->getName());die;
//            $item[$this->getData('name')] = $this->prepareItem($item);
            $item['product_name'] = $product->getName();
        }
    }
//    print_r($dataSource);die;
    return $dataSource;
}
}
