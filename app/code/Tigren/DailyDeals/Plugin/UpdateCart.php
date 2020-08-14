<?php


namespace Tigren\DailyDeals\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
/**
 * Class afterUpdateItems
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateCart
{
    protected $_dealCollection;

    function __construct(DealCollection $dealCollection)
    {
        $this->_dealCollection = $dealCollection;
    }

    public function afterUpdateItems($subject, $result, $data)
    {
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
                $dealQty = $deals->getItemByColumnValue('product_sku', $itemSku)->getData('deal_qty');
                if ($newQty > $dealQty) {
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
