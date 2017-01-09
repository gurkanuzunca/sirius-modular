<?php

namespace Sirius\Admin;


abstract class Actuator extends Controller
{
    public $definitions;
    public $table;
    public $columns = array();
    public $orders = array();
    public $groupsToPositions = array();
    public $groups = array(
        'default' => array(
            'title' => 'Genel',
            'position' => 'left',
            'order' => 1
        ),
        'publish' => array(
            'title' => 'Yayımla',
            'position' => 'right',
            'order' => 9
        ),
        'meta' => array(
            'title' => 'Meta Bilgileri',
            'position' => 'right',
            'order' => 10
        )
    );

    public $positions = array(
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
                'show' => array(
                    'list' => true,
                    'insert' => true,
                    'update' => true
                ),
                'options' => ['published' => 'Yayında', 'unpublished' => 'Yayında Değil'],
                'default' => 'published',
                'styles' => [
                    'published' => '<span class="label label-success">Yayında</span>',
                    'unpublished' => '<span class="label label-danger">Yayında Değil</span>'
                ],
                'width' => '150'
            ),
            'createdAt' => array(
                'label' => 'Oluşturulma Tarihi',
                'type' => 'datetime',
                'insert' => true,
                'show' => array(
                    'update' => true
                ),
                'group' => 'publish',
                'default' => 'now',
                'disabled' => true
            ),
            'updatedAt' => array(
                'label' => 'Güncellenme Tarihi',
                'type' => 'datetime',
                'update' => true,
                'show' => array(
                    'update' => true
                ),
                'group' => 'publish',
                'default' => 'now',
                'disabled' => true
            )
        ),
        'meta' => array(
            'metaTitle' => array(
                'label' => 'Title',
                'type' => 'text',
                'insert' => true,
                'update' => true,
                'show' => array(
                    'insert' => true,
                    'update' => true
                )

            ),
            'metaDescription' => array(
                'label' => 'Description',
                'type' => 'textarea',
                'insert' => true,
                'update' => true,
                'show' => array(
                    'insert' => true,
                    'update' => true
                )
            ),
            'metaKeywords' => array(
                'label' => 'Keywords',
                'type' => 'textarea',
                'insert' => true,
                'update' => true,
                'show' => array(
                    'insert' => true,
                    'update' => true
                )
            )
        )
    );


    public function __construct()
    {
        if (empty($this->table)) {
            throw new \Exception('Actuator table tanimlanmamis.');
        }

        if (empty($this->definitions['columns'])) {
            throw new \Exception('Actuator columns tanimlanmamis.');
        }

        $this->model = 'Actuator';


        if (isset($this->definitions['groups'])) {
            $this->groups = array_merge($this->groups, $this->definitions['groups']);
        }

        if (isset($this->definitions['positions'])) {
            $this->positions = array_merge($this->positions, $this->definitions['positions']);
        }

        foreach ($this->groups as $group => $options) {
            if (! isset($this->groupsToPositions[$options['position']])) {
                $this->groupsToPositions[$options['position']] = array();
            }

            $this->groupsToPositions[$options['position']][] = $group;
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
            $this->columns = array_merge($this->columns, $group);
        }

        foreach ($this->columns as $column => $options) {
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


    public function insert()
    {
        parent::callInsert();
        $this->assets->importEditor();
        $this->render('insert', true);
    }

    public function update()
    {
        parent::callUpdate();
        $this->assets->importEditor();
        $this->render('update', true);
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
        $rules = [];

        foreach ($this->columns as $column => $options) {
            if (isset($options['validation'])) {
                if (isset($options['validation'][$action])) {
                    $rules[$column] = $options['validation'][$action];
                } else {
                    $rules[$column] = $options['validation'];
                }
            }

        }
        $this->validate($rules);
    }


    public function createForm($type, $group, $record = null)
    {
        $response = '';

        foreach ($this->definitions['columns'][$group] as $column => $options) {
            if (isset($options['show'][$type]) && $options['show'][$type] === true) {
                $value = null;
                if ($type === 'update' && ! is_null($record)) {
                    $value = $record->$column;
                }

                switch ($options['type']) {
                    case 'textarea': {
                        $response .= bsFormTextarea($column, $options['label'], [
                            'value' => $value,
                            'disabled' => isset($options['disabled']) ? $options['disabled'] : false
                        ]);
                        break;
                    }
                    case 'dropdown': {
                        $response .= bsFormDropdown($column, $options['label'], [
                            'options' => $options['options'],
                            'value' => $value,
                            'disabled' => isset($options['disabled']) ? $options['disabled'] : false
                        ]);
                        break;
                    }
                    case 'editor': {
                        $response .= bsFormEditor($column, $options['label'], [
                            'value' => $value,
                            'disabled' => isset($options['disabled']) ? $options['disabled'] : false
                        ]);
                        break;
                    }
                    case 'datetime': {
                        $response .= bsFormDatetime($column, $options['label'], [
                            'value' => $value ? $this->date->set($value)->datetimeWithName() : $value,
                            'disabled' => isset($options['disabled']) ? $options['disabled'] : false
                        ]);
                        break;
                    }
                    default: {
                        $response .= bsFormText($column, $options['label'], [
                            'value' => $value,
                            'disabled' => isset($options['disabled']) ? $options['disabled'] : false
                        ]);
                    }
                }
            }
        }

        return $response;
    }

}