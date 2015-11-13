<?php

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\Factory;

class ShopifyResourceOwnerObjectFactory extends AbstractShopifyResourceOwnerFactory
{
    /**
     * @var ShopifyShopAwareInterface
     */
    private $object;

    /**
     * @param ShopifyShopAwareInterface|null $object
     */
    public function __construct($object = null)
    {
        if ($object !== null) {
            $this->setObject($object);
        }
    }

    /**
     * @param ShopifyShopAwareInterface $object
     */
    public function setObject(ShopifyShopAwareInterface $object)
    {
        $this->object = $object;
    }

    /**
     * @return ShopifyShopAwareInterface
     */
    public function getObject()
    {
        if (!$this->object) {
            throw new \RuntimeException('No object with knowledge of a Shopify shop has been configured.');
        }

        return $this->object;
    }

    /**
     * {@inheritdoc}
     */
    public function getShopifyShop()
    {
        return $this->object->getShopifyShop();
    }
}
