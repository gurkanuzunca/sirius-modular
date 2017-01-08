<?php

namespace Sirius\Admin;


class ActuatorModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function find($id)
    {
        return $this->db
            ->from($this->table)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function all($paginate = [])
    {
        $this->setFilter();
        $this->setPaginate($paginate);

        foreach ($this->orders as $column => $sort) {
            $this->db->order_by($column, $sort);
        }

        return $this->db
            ->from($this->table)
            ->where('language', $this->language)
            ->get()
            ->result();
    }


    public function count()
    {
        $this->setFilter();

        return $this->db
            ->from($this->table)
            ->where('language', $this->language)
            ->count_all_results();
    }


    public function insert($data = array())
    {
        /** Query builder çakıştığı için değişkene aktarılması gerekmekte. */
        $insert = $this->createData('insert');
        $this->db->insert($this->table, $insert);

        $insertId = $this->db->insert_id();

        if ($insertId > 0) {
            return $this->find($insertId);
        }

        return false;
    }


    public function update($record, $data = array())
    {
        /** Query builder çakıştığı için değişkene aktarılması gerekmekte. */
        $update = $this->createData('update');
        $this->db->where('id', $record->id)->update($this->table, $update);

        if ($this->db->affected_rows() > 0) {
            return $this->find($record->id);
        }

        return false;
    }



    public function delete($data)
    {
        $records = parent::callDelete($this->table, $data, true);

        if (empty($records)){
            return false;
        }

        return true;
    }

    public function order($ids)
    {
        return parent::callOrder($this->table, $ids);
    }


    private function createData($for)
    {
        $data = array(
            'language' => $this->language
        );

        foreach ($this->columns as $column => $options) {
            if (isset($options[$for]) && $options[$for] === true) {
                if ($options['type'] === 'slug') {
                    $data[$column] = $this->makeSlug();
                } elseif ($options['type'] === 'order') {
                    $data[$column] = $this->makeLastOrder(array(), $column);
                } elseif ($options['type'] === 'datetime') {
                    $data[$column] = $this->date->set(isset($options['default']) ? $options['default'] : 'now')->mysqlDatetime();
                } else {
                    if ($this->input->post($column)) {
                        $value = $this->input->post($column);
                    } else {
                        $value = isset($options['default']) ? $options['default'] : null;
                    }
                    $data[$column] = $value;
                }
            }
        }

        return $data;

    }
}