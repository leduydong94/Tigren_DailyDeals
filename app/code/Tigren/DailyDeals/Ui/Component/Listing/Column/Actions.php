<?php
namespace Tigren\DailyDeals\Ui\Component\Listing\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Tigren\DailyDeals\Block\Adminhtml\Module\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;
class Actions extends Column {
    /** Url path */
    const URL_PATH_EDIT = 'daily/deals/edit';
    const URL_PATH_ROW_DELETE = 'daily/deals/rowdelete';
    /** @var UrlBuilder */
    protected $actionUrlBuilder;
    /** @var UrlInterface */
    protected $urlBuilder;
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlBuilder $actionUrlBuilder, UrlInterface $urlBuilder, array $components = [], array $data = []) {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**ar_dump($item['id']);
    //                        exit;
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    $item[$name]['edit'] = ['href' => $this->urlBuilder->getUrl(self::URL_PATH_EDIT, ['id' => $item['id']]), 'label' => __('Edit') ];
                    $item[$name]['delete'] = ['href' => $this->urlBuilder->getUrl(self::URL_PATH_ROW_DELETE,['id' => $item['id']]), 'label' => __('Delete') ];
                }
            }
        }
        return $dataSource;
    }
}
