<?php

namespace Controllers;

use Sirius\Application\Controller;


abstract class BaseController extends Controller
{
    public $language;

    public function __construct()
    {
        parent::__construct();

        /**
         * Kurulumun yapılıp yapılmadığını kontrol eder.
         * Kurulum yapılmadıysa kurulum ekranına geçer.
         */
        $this->isReady();

        /**
         * Varsayılan dil atama
         */
        $languages = $this->config->item('languages');
        $segment = $this->uri->segment(1);

        if ($languages && $segment) {
            if (array_key_exists($segment, $languages)) {
                $language = $segment;
            }
        }

        if (! empty($language)){
            $this->config->set_item('language', $language);
            $this->language = $language;
        } else {
            $this->language = $this->config->item('language');
        }

        $this->lang->load('application');


        /**
         * Site genel ayarları atama
         */
        $results =  $this->db
            ->where('language', $this->language)
            ->or_where('language', null)
            ->get('options')
            ->result();

        foreach ($results as $result) {
            $this->stack->set("options.{$result->name}", $result->value);
        }


        /**
         * Belirtilen modüle argümanlarını atama
         */
        if (isset($this->module)) {

            $module = $this->getModule($this->module);

            if ($module) {
                $this->module = $module;

                if (! empty($this->module->arguments->metaTitle)) {
                    $this->stack->set('options.metaTitle', $this->module->arguments->metaTitle);
                }

                if (! empty($this->module->arguments->metaDescription)) {
                    $this->stack->set('options.metaDescription', $this->module->arguments->metaDescription);
                }

                if (! empty($this->module->arguments->metaKeywords)) {
                    $this->stack->set('options.metaKeywords', $this->module->arguments->metaKeywords);
                }

            } else {
                unset($this->module);
            }
        }
    }


    /**
     * Kurulumun yapılıp yapılmadığını kontrol eder.
     * Kurulum yapılmadıysa kurulum ekranına geçer.
     */
    public function isReady()
    {
        if (! $this->db->table_exists('options')) {
            redirect('admin/install');
        }
    }

}