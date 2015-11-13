<?php

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\Factory;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShopifyResourceOwnerSessionFactory extends AbstractShopifyResourceOwnerFactory
{
    const SESSION_PARAM_NAME = 'shopify_shop';
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
     * {@inheritdoc}
     */
    public function getShopifyShop()
    {
        return $this->session->get(self::SESSION_PARAM_NAME);
    }
}
