<?php

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\Factory;

interface ShopifyShopAwareInterface
{
    /**
     * @return string
     */
    public function getShopifyShop();
}
