<?php


namespace Tigren\DailyDeals\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
/**
 * Class afterUpdateItems
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateCart
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

    public function afterUpdateItems($subject, $result, $data)
    {
        $config = $this->_scopeConfig->getValue('dailydeals_section_id/general/enable');
        $deals = $this->_dealCollection->create();
        $dealSku = [];
        foreach ($deals as $deal) {
            $dealSku[] = $deal->getData('product_sku');
        }

        foreach ($data as $itemId => $itemInfo) {
            $item = $subject->getQuote()->getItemById($itemId);
            $itemSku = $item['sku'];
            $newQty = $itemInfo['qty'];
            if (in_array($itemSku, $dealSku)) {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $endTime = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('end_date_time');
                $startTime = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('start_date_time');
                $now = date('Y-m-d H:i:s');
                $status = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('status');
                $dealQty = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('deal_qty');
                if ($now <= $endTime && $status == 1 && $now >= $startTime && $config != 0 && $newQty > $dealQty) {
//                if ($newQty > $dealQty) {
                    throw new LocalizedException(__('You can not add more than deal quantity'));
                } else {
                    $item->setQty($newQty);
                }
            } else {
                $item->setQty($newQty);
            }
        }
        return $result;
    }
}
