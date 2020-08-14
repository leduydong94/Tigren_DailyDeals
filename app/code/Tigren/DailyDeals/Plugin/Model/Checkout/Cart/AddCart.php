<?php
namespace Tigren\DailyDeals\Plugin\Model\Checkout\Cart;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddCart
{
    protected $_dealCollection;
    protected $_scopeConfig;

    function __construct(
        DealCollection $dealCollection,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->_dealCollection = $dealCollection;
        $this->_scopeConfig = $scopeConfig;
    }

    public function aroundAddProduct(
        Cart $subject,
        callable $proceed,
        $productInfo,
        $requestInfo = null
    )
    {
        $config = $this->_scopeConfig->getValue('dailydeals_section_id/general/enable');
        $deals = $this->_dealCollection->create();
        $dealSku = [];
        foreach ($deals as $deal) {
            $dealSku[] = $deal->getData('product_sku');
        }
        $productSku = $productInfo->getSku();

        if (isset($requestInfo['qty'])) {
            $addQty = $requestInfo['qty'];//get qty before add to cart
        } else {
            $addQty = 1;
        }

        if (in_array($productSku, $dealSku)) {
            $dealQty = $deals->getItemByColumnValue('product_sku', $productSku)->getData('deal_qty');
            $startTime = $deals->getItemByColumnValue('product_sku', $productSku)->getData('start_date_time');
            $endTime = $deals->getItemByColumnValue('product_sku', $productSku)->getData('end_date_time');
            $status = $deals->getItemByColumnValue('product_sku', $productSku)->getData('status');
            $now = date('Y-m-d H:i:s');

            $items = $subject->getQuote()->getAllItems();
            $productQty = 0;
            foreach ($items as $item) {
                $product = array(
                    'sku' => $item->getSku(),
                    'quantity' => $item->getQty()
                );
                if ($product['sku'] == $productSku) {
                    $productQty += $product['quantity'];
                }
            }

            if ($now <= $endTime && $status == 1 && $now >= $startTime && $config != 0
                && ($productQty + $addQty) > $dealQty) {
                throw new LocalizedException(__('Deal Quantity Limited'));
            } else {
                return $proceed($productInfo, $requestInfo);
            }

        } else {
            return $proceed($productInfo, $requestInfo);
        }
    }
}
