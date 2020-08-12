<?php
namespace Tigren\DailyDeals\Block\Frontend;

use Magento\Framework\View\Element\Template;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;

class Countdown extends Template
{
    protected $dealCollection;
    protected $productCollection;
    protected $productRepository;
    protected $registry;

    public function __construct(
        Template\Context $context,
        DealCollection $dealCollection,
        \Magento\Framework\Registry $registry
    )
    {
        $this->dealCollection = $dealCollection;
        $this->registry = $registry;
        parent::__construct($context);
    }

    public function GetDataBySku($sku, $key)
    {
        $deal = $this->dealCollection->create();
        $dealSku = [];
        foreach ($deal as $dealItem) {
            $dealSku[] = $dealItem->GetData('product_sku');
        }
        if (in_array($sku,$dealSku)){
            return $deal->getItemByColumnValue('product_sku', $sku)->getData($key);
        } else {
            return null;
        }
    }

    public function GetCurrentSku(){
        return $this->registry->registry('current_product');
    }
}
