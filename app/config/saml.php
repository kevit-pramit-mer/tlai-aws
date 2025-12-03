<?php

return [
    'class' => 'app\components\DynamicSamlConnection',
    'config' => [
        'debug' => true,
        'sp' => [
            'entityId' => '',
            'assertionConsumerService' => [
                'url' => '',
            ],
            'singleLogoutService' => [
                'url' => '',
            ],
        ],
        'idp' => [
            'entityId' => '',
            'singleSignOnService' => [
                'url' => '',
            ],
            /* 'singleLogoutService' => [
                 'url' => 'https://accounts.google.com/o/saml2?idpid=C01tsbdju',
             ],*/
            'x509cert' => '',
        ],
    ],
];
