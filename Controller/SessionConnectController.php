<?php

namespace HWI\Bundle\OAuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class SessionConnectController extends ConnectController
{
    /**
     * {@inheritdoc}
     */
    public function redirectToServiceAction(Request $request, $service)
    {
        $sessionParameters = $request->attributes->get('session_parameters', []);

        foreach ($sessionParameters as $parameterName) {
            $parameterValue = $request->get($parameterName);

            $request->getSession()->set($parameterName, $parameterValue);
        }

        return parent::redirectToServiceAction($request, $service);
    }
}
