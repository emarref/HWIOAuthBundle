<?php

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\Factory;

use Buzz\Client\ClientInterface;
use HWI\Bundle\OAuthBundle\OAuth\RequestDataStorageInterface;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\ShopifyResourceOwner;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\HttpUtils;

class ShopifyResourceOwnerFactory
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $shop
     * @return string
     */
    protected function normaliseBaseUrl($shop)
    {
        $baseUrl = $shop;

        // Get rid of trailing slashes.
        $baseUrl = trim($baseUrl, '/');

        // Append myshopify.com if given shop name on its own
        if (false === strpos($shop, '.')) {
            $baseUrl .= '.myshopify.com';
        }

        // Ensure we haven't been given a custom domain.
        if (!preg_match('/\.myshopify\.com$/', $baseUrl)) {
            throw new \RuntimeException(sprintf('Cannot use custom domain "%s" for shopify API calls.', $baseUrl));
        }

        // Strip the protocol if given.
        if (preg_match('/^https?:\/\//', $baseUrl)) {
            $baseUrl = substr($baseUrl, strpos($baseUrl, '//') + 2);
        }

        return 'https://' . $baseUrl;
    }

    /**
     * @param ClientInterface             $httpClient
     * @param HttpUtils                   $httpUtils
     * @param array                       $options
     * @param string                      $name
     * @param RequestDataStorageInterface $storage
     * @return ShopifyResourceOwner
     */
    public function get(ClientInterface $httpClient, HttpUtils $httpUtils, array $options, $name, RequestDataStorageInterface $storage)
    {
        $shop = $this->session->get('shopify_shop');

        if (empty($shop)) {
            throw new \RuntimeException('No shopify shop found in session.');
        }

        $options = array_merge($options, [
            'base_url' => $this->normaliseBaseUrl($shop),
        ]);

        return new ShopifyResourceOwner($httpClient, $httpUtils, $options, $name, $storage);
    }
}
