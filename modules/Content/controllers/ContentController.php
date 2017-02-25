<?php

use Controllers\BaseController;

class ContentController extends BaseController
{
    public $module = 'content';

    public function view($slug)
    {
        $this->load->model('content');

        if (! $content = $this->content->findBySlug($slug)) {
            show_404();
        }

        $this->setMeta($content, ['type' => 'article', 'imagePath' => 'content']);

        /**
         * Rezerve için view varsa yada alt kayıt varsa
         * ilgili view kontrolleri.
         */
        $activeView = 'content/view';
        if (! empty($content->childs)) {
            //$activeView = 'content/list';
        }

        if (! empty($content->reserved) && file_exists(APPPATH . 'views/content/reserved/' . $content->reserved . '.php')) {
            $activeView = 'content/reserved/' . $content->reserved;
        }

        $this->render($activeView, array(
            'content' => $content
        ));


    }



} 