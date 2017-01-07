<?php

use Sirius\Admin\Installer as InstallManager;


class Installer extends InstallManager
{

    /**
     * Tabloların varlığı kontrol edilmesi için konulabilir.
     *
     * @var array
     */
    public $tables = array(
        'news'
    );


    /**
     * Rotasyon tanımlamaları.
     *
     * @var array
     */
    public $routes = array(
        'tr' => array(
            'route' => array(
                '@uri/([a-zA-Z0-9_-]+)' => 'News/NewsController/view/$1',
            ),
            'uri' => 'haberler'
        ),
        'en' => array(
            'route' => array(
                '@uri/([a-zA-Z0-9_-]+)' => 'News/NewsController/view/$1',
            ),
            'uri' => 'news'
        ),
    );
}