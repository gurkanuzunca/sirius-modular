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
    );

    public $search = array('title');


    public $table = 'news';
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
                    'validation' => array('required', 'Lütfen başlık girin.')
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
                    'validation' => array('required', 'Lütfen özet girin.')
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
                    'validation' => array('required', 'Lütfen içerik girin.')
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