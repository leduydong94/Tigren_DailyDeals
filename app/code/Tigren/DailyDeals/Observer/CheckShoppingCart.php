<?php
namespace Tigren\DailyDeals\Observer;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckShoppingCart implements ObserverInterface
{
    protected $cart;
    protected $dealCollection;
    protected $_scopeConfig;
    protected $_product;

    public function __construct(
        DealCollection $dealCollection,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        Cart $cart
    )
    {
        $this->dealCollection = $dealCollection;
        $this->cart = $cart;
        $this->_scopeConfig = $scopeConfig;
        $this->productFactory = $productFactory;
    }

    public function execute(Observer $observer)
    {
        $config = $this->_scopeConfig->getValue('dailydeals_section_id/general/enable');
        $product = $this->productFactory->create();
        $deals = $this->dealCollection->create();
        $dealSku = [];
        foreach ($deals as $dealItem) {
            $dealSku[] = $dealItem->getData('product_sku');
        }

        $quote = $this->cart->getQuote();
        $cartItems = $quote->getAllVisibleItems();

        foreach($cartItems as $item) {
            $itemSku = $item->getSku();
            $productPrice = $product->loadByAttribute('sku', $itemSku)->getPrice();

            if (in_array($itemSku, $dealSku)) {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $endTime = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('end_date_time');
                $startTime = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('start_date_time');
                $now = date('Y-m-d H:i:s');
                $status = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('status');
                $dealPrice = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('deal_price');

                if ($now > $endTime || $now < $startTime || $status != 1 || $config == 0) {
                    $item->setCustomPrice($productPrice);
                    $item->setOriginalCustomPrice($productPrice);
                } else {
                    $item->setCustomPrice($dealPrice);
                    $item->setOriginalCustomPrice($dealPrice);
                }

                $item->getProduct()->setIsSuperMode(true);
                $item->save();
            }
        }
        $quote->collectTotals()->save();
        return $this;
    }
}
