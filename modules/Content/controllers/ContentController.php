<?php

use Sirius\Application\Controller;

class ContentController  extends Controller
{
    public $module = 'content';

    public function view($id)
    {
        $this->load->model('content');

        if (! $content = $this->content->find($id)) {
            show_404();
        }

        $this->setMeta($content, 'article');

        /**
         * Rezerve için view varsa yada alt kayıt varsa
         * ilgili view kontrolleri.
         */
        $activeView = 'content/view';
        if (! empty($content->childs)) {
            $activeView = 'content/list';
        }

        if (! empty($content->reserved) && file_exists(APPPATH . 'views/content/reserved/' . $content->reserved . '.php')) {
            $activeView = 'content/reserved/' . $content->reserved;
        }

        $this->load->view('master', array(
            'view' => $activeView,
            'content' => $content
        ));


    }



} 