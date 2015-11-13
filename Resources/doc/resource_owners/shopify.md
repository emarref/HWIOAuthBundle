Step 2x: Setup Shopify
=================================

First you will have to register your application with [Shopify](https://app.shopify.com/services/partners/api_clients/new).

Next configure a resource owner of type `shopify` with appropriate `client_id`, `client_secret` and `scope`.
This information can be found on the edit page for the Shopify application you just created.

There are two different ways to configure Shopify for your application.

### Single Shop

If your visitors will only ever connect to one explicit Shopify shop, configure your provider as you would any other
and you're done:

```yaml
# app/config/config.yml

hwi_oauth:
    resource_owners:
        any_name:
            type:          shopify
            client_id:     <client_id>
            client_secret: <client_secret>
            scope:         "read_products,write_orders"
            base_url:      https://myshop.myshopify.com
```

### Any Shop

Alternatively, if you'd like to allow the visitor to connect to any shop, the configuration is slightly more complex.

First, create a Shopify resource owner by using the provided factory:

```yaml
# app/config/services.yml

services:
    acme.oauth.resource_owner.shopify:
        class:   %hwi_oauth.resource_owner.shopify.class%
        factory: [@hwi_oauth.abstract_resource_owner_factory.shopify, get]
        arguments:
            - @hwi_oauth.http_client
            - @security.http_utils
            - #options
                client_id:     <client_id>
                client_secret: <client_secret>
                scope:         "read_products,write_orders"
            - shopify #name
            - @hwi_oauth.storage.session
```

Next, configure your Shopify resource owner with the HWIOAuthBundle, prodiving your new service as the resource owner:

```yaml
# app/config/config.yml

hwi_oauth:
    resource_owners:
        any_name:
            service: acme.oauth.resource_owner.shopify
```

As the user will need to specify which shop they are connecting to before you can redirect them there, you will need to
gather that information first, and put it into the session. The easiest way to do this is to add a route that points to
the `SessionConnectController::redirectToServiceAction()` action.

```yaml
# app/config.routing.yml

shopify_connect:
    path: /connect/{service}
    requirements:
        service: ^shopify$
    defaults:
        _controller: HWIOAuthBundle:SessionConnect:redirectToService
        session_parameters: [shopify_shop]
```

This will take any request query parameter listed in the `session_parameters` variable, and pass it into the session
under the same name.

Now when you visit `/connect/shopify?shop=myshop.myshopify.com`, you will continue on to the Shopify login process as
normal.

For example, given the above configuration, the below form would allow the user to enter their store name to connect.

```html
<form action="{{ path('shopify_connect', {'service':'shopify'}) }}" method="get">
    <label for="shopify_shop">Shopify shop:</label>
    <input type="text" id="shopify_shop" name="shopify_shop" placeholder="shop.myshopify.com"/>
    <button type="submit">Go</button>
</form>
```

When you're done. Continue by configuring the security layer or go back to setup more resource owners.

- [Step 2: Configuring resource owners (Facebook, GitHub, Google, Windows Live and others](../2-configuring_resource_owners.md)
- [Step 3: Configuring the security layer](../3-configuring_the_security_layer.md).
