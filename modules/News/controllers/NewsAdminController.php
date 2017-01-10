<?php

use Sirius\Admin\Actuator;

class NewsAdminController extends Actuator
{
    public $moduleTitle = 'Haberler';
    public $module = 'news';
    public $model = 'actuator';
    public $icon = 'fa-newspaper-o';
    public $type = 'public';
    public $menuPattern = array(
        'table' => 'news',
        'title' => 'title',
        'hint' => 'title',
        'link' => array('slug'),
        'language' => true
    );

    public $actions = array(
        'records' => 'list',
        'insert' => 'insert',
        'update' => 'update',
        'delete' => 'delete',
        'order' => 'list',
        'images' => 'image-list',
        'imageOrder' => 'image-list',
        'imageInsert' => 'image-insert',
        'imageUpdate' => 'image-update',
        'imageDelete' => 'image-delete',
    );

    public $search = array('title');


    public $table = 'news';
    public $images = true;
    public $imageTable = 'news_images';
    public $definitions = array(
        'columns' => array(
            'default' => array(
                'title' => array(
                    'label' => 'Başlık',
                    'type' => 'text',
                    'insert' => true,
                    'update' => true,
                    'show' => array(
                        'list' => true,
                        'insert' => true,
                        'update' => true
                    ),
                    'validation' => array('required', 'Lütfen başlık girin.'),
                    'required' => true
                ),
                'slug' => array(
                    'label' => 'Slug',
                    'type' => 'slug',
                    'insert' => true,
                    'update' => true,
                    'show' => array(
                        'list' => true,
                        'insert' => true,
                        'update' => true
                    )
                ),
                'summary' => array(
                    'label' => 'Özet',
                    'type' => 'textarea',
                    'insert' => true,
                    'update' => true,
                    'show' => array(
                        'insert' => true,
                        'update' => true
                    ),
                    'validation' => array('required', 'Lütfen özet girin.'),
                    'required' => true
                ),
                'image' => array(
                    'label' => 'Görsel',
                    'type' => 'image',
                    'insert' => true,
                    'update' => true,
                    'show' => array(
                        'insert' => true,
                        'update' => true
                    ),
                    'size' => array(480, 360),
                    'process' => array(
                        'news' => ['thumbnail' => [480, 360]]
                    ),
                    'required' => true
                ),
                'content' => array(
                    'label' => 'İçerik',
                    'type' => 'editor',
                    'insert' => true,
                    'update' => true,
                    'show' => array(
                        'insert' => true,
                        'update' => true
                    ),
                    'validation' => array('required', 'Lütfen içerik girin.'),
                    'required' => true
                ),
                'order' => array(
                    'label' => 'Sıra',
                    'type' => 'order',
                    'insert' => true,
                    'show' => array(
                        'list' => true
                    ),
                    'sort' => 'asc',
                    'class' => 'text-center',
                    'width' => '100'
                )
            ),
            'publish' => true,
            'meta' => true,
        )
    );

}