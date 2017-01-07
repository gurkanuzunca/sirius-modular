<?php

namespace Sirius\Admin;


class Actuator extends Controller
{
    public $definitions;
    public $table;
    public $fields = array();
    public $orders = array();
    private $groups = array(
        'default' => array(
            'position' => 'left',
            'order' => 1
        ),
        'publish' => array(
            'position' => 'right',
            'order' => 9
        ),
        'meta' => array(
            'position' => 'right',
            'order' => 10
        )
    );

    private $positions = array(
        'left' => 'col-sm-8',
        'right' => 'col-sm-4'
    );

    private $defaults = array(
        'publish' => array(
            'status' => array(
                'label' => 'Durum',
                'type' => 'dropdown',
                'insert' => true,
                'update' => true,
                'options' => ['published' => 'Yayında', 'unpublished' => 'Yayında Değil'],
                'default' => 'published'
            ),
            'createdAt' => array(
                'label' => 'Oluşturulma Tarihi',
                'type' => 'datetime',
                'insert' => true,
                'update' => true,
                'group' => 'publish',
                'options' => ['published' => 'Yayında', 'unpublished' => 'Yayında Değil'],
                'default' => 'now',
                'disabled' => true
            ),
            'updatedAt' => array(
                'label' => 'Güncellenme Tarihi',
                'type' => 'datetime',
                'update' => true,
                'group' => 'publish',
                'options' => ['published' => 'Yayında', 'unpublished' => 'Yayında Değil'],
                'default' => 'now',
                'disabled' => true
            )
        ),
        'meta' => array(
            'metaTitle' => array(
                'label' => 'Title',
                'type' => 'text',
                'insert' => true,
                'update' => true
            ),
            'metaDescription' => array(
                'label' => 'Description',
                'type' => 'textarea',
                'insert' => true,
                'update' => true
            ),
            'metaKeywords' => array(
                'label' => 'Keywords',
                'type' => 'textarea',
                'insert' => true,
                'update' => true
            )
        )
    );


    public function __construct()
    {
        if (empty($this->definitions['table'])) {
            throw new \Exception('Actuator table tanimlanmamis.');
        }

        if (empty($this->definitions['columns'])) {
            throw new \Exception('Actuator columns tanimlanmamis.');
        }

        $this->model = 'Actuator';
        $this->table = $this->definitions['table'];


        if (isset($this->definitions['groups'])) {
            $this->definitions['groups'] = array_merge($this->groups, $this->definitions['groups']);
        } else {
            $this->definitions['groups'] = $this->groups;
        }

        if (isset($this->definitions['positions'])) {
            $this->definitions['positions'] = array_merge($this->positions, $this->definitions['positions']);
        } else {
            $this->definitions['positions'] = $this->positions;
        }


        if (isset($this->definitions['columns']['meta'])) {
            if ($this->definitions['columns']['meta'] === true) {
                $this->definitions['columns']['meta'] = $this->defaults['meta'];
            } else {
                $this->definitions['columns']['meta'] = array_merge($this->defaults['meta'], $this->definitions['columns']['meta']);
            }
        }


        if (isset($this->definitions['columns']['publish'])) {
            if ($this->definitions['columns']['publish'] === true) {
                $this->definitions['columns']['publish'] = $this->defaults['publish'];
            } else {
                $this->definitions['columns']['publish'] = array_merge($this->defaults['publish'], $this->definitions['columns']['publish']);
            }
        }

        foreach ($this->definitions['columns'] as $group) {
            $this->fields = array_merge($this->fields, $group);
        }

        foreach ($this->fields as $column => $options) {
            if ($options['type'] === 'order') {
                if (! isset($options['sort'])) {
                    throw new \Exception($column. 'alanı siralama yonu tanimlanmamis.');
                }

                $this->orders[$column] = $options['sort'];
            }
        }

        $this->orders['id'] = 'asc';


        parent::__construct();
    }

    public function records()
    {
        parent::callRecords();
        $this->render('records', true);
    }

}