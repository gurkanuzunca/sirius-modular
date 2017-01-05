<?php
use Controllers\AdminController;

class ContentAdminController extends AdminController
{
    public $moduleTitle = 'İçerikler';
    public $module = 'content';
    public $model = 'content';
    public $icon = 'fa-newspaper-o';
    public $type = 'public';
    public $menuPattern = array(
        'table' => 'contents',
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


    public function records()
    {
        /**
         * Eğer parent verilmişse breadcrump hazırlanır.
         * Parent değeri view'a atanır.
         */
        if ($this->uri->segment(4) > 0) {
            if (! $parent = $this->appmodel->find($this->uri->segment(4))) {
                show_404();
            }

            $this->setParentsBread($parent);
            $this->viewData['parent'] = $parent;
        }

        parent::callRecords([
            'count' => [$this->appmodel, 'count', isset($parent) ? $parent : null],
            'all' => [$this->appmodel, 'all', isset($parent) ? $parent : null]
        ]);

        $this->render('records');
    }


    public function insert()
    {
        /**
         * Eğer parent verilmişse breadcrump hazırlanır.
         * Parent değeri view'a atanır.
         */
        if ($this->uri->segment(4) > 0) {
            if (! $parent = $this->appmodel->find($this->uri->segment(4))) {
                show_404();
            }

            $this->setParentsBread($parent);
            $this->viewData['parent'] = $parent;
        }

        parent::callInsert([
            'insert' => [$this->appmodel, 'insert', isset($parent) ? $parent : null],
        ]);
        $this->assets->importEditor();
        $this->render('insert');
    }

    public function update()
    {
        parent::callUpdate();
        $this->assets->importEditor();
        $this->render('update');
    }

    public function delete()
    {
        parent::callDelete();
    }

    public function order()
    {
        parent::callOrder();
    }

    public function validation($action, $record = null)
    {
        $this->validate([
            'title' => ['required', 'Lütfen başlık yazın.']
        ]);
    }

    public function validationAfter($action, $record = null)
    {
        $this->image->setMinSizes(480, 360)
            ->addProcess('content', ['thumbnail' => [480, 360]]);

        $this->modelData['image'] = $this->image->save();
    }

    private function setParentsBread($record)
    {
        $parents = $this->appmodel->parents($record->id);

        foreach ($parents as $bread){
            $this->utils->breadcrumb($bread['title'], $bread['url']);
        }
    }
}