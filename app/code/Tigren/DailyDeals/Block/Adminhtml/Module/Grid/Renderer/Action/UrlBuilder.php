<?php
namespace Tigren\DailyDeals\Block\Adminhtml\Module\Grid\Renderer\Action;
use Magento\Framework\UrlInterface;

class UrlBuilder
{
    /**
     * @var UrlInterface
     */
    protected $frontendUrlBuilder;

    /**
     * @param UrlInterface $frontendUrlBuilder
     */
    public function __construct(UrlInterface $frontendUrlBuilder)
    {
        $this->frontendUrlBuilder = $frontendUrlBuilder;
    }

    /**
     * Get action url
     *
     * @param string $routePath
     * @param string $scope
     * @param string $store
     * @return string
     */
    public function getUrl($routePath, $scope, $store)
    {
        $this->frontendUrlBuilder->setScope($scope);
        $href = $this->frontendUrlBuilder->getUrl($routePath, ['_current' => false, '_query' => '___store=' . $store]);
        return $href;
    }
}
