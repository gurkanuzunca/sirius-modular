<?php

use Controllers\BaseController;

class NewsController extends BaseController
{
    public $module = 'news';
    public $table = 'news';
    public $status = true;
    public $parent = true;
    public $images = true;
    public $imageTable = 'news_images';
    public $orders = array(
        'order' => 'asc',
        'id' => 'asc'
    );
    public $actuator = true;


    public function index()
    {
        $news = $this->news->all();

        $this->render('news/index', array(
            'news' => $news
        ));
    }


    public function view($slug)
    {
        if (! $news = $this->news->findBySlug($slug)) {
            show_404();
        }

        $this->setMeta($news, 'article');

        $this->render('news/view', array(
            'news' => $news
        ));
    }



} 