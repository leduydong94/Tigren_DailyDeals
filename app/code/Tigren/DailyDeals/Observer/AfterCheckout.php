<?php

namespace Tigren\DailyDeals\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
use Tigren\DailyDeals\Model\DealsFactory;

class AfterCheckout implements ObserverInterface
{
    protected $dealCollection;
    protected $dealsFactory;

    public function __construct(
        DealCollection $dealCollection,
        ScopeConfigInterface $scopeConfig,
        DealsFactory $dealsFactory
    )
    {
        $this->dealCollection = $dealCollection;
        $this->dealsFactory = $dealsFactory;
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $config = $this->_scopeConfig->getValue('dailydeals_section_id/general/enable');
        $deals = $this->dealCollection->create();
        $dealSku = [];
        foreach ($deals as $dealItem) {
            $dealSku[] = $dealItem->getData('product_sku');
        }

        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();

        foreach ($items as $item) {
            $itemSku = $item->getSku();
            $itemQty = $item->getQtyOrdered();
            $dealId = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('id');
            $dealQty = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('deal_qty');
            $deal = $this->dealsFactory->create()->load($dealId);
            $dealQty -=  $itemQty;
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $endTime = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('end_date_time');
            $startTime = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('start_date_time');
            $now = date('Y-m-d H:i:s');
            $status = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('status');

            if (in_array($itemSku, $dealSku)) {
                if ($now <= $endTime && $status == 1 && $now >= $startTime && $config != 0) {
                    $deal->setData('deal_qty', $dealQty);
                    $deal->save();
                }
            }
        }
        return $this;
    }
}
