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
        $insert = $this->createData('insert', $data);
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
        $update = $this->createData('update', $data);
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


    public function image($id)
    {
        return $this->db
            ->from($this->imageTable)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function imageAll($parent, $paginate = [])
    {
        $this->setFilter();
        $this->setPaginate($paginate);

        foreach ($this->imageOrders as $column => $sort) {
            $this->db->order_by($column, $sort);
        }

        return $this->db
            ->from($this->imageTable)
            ->where('parentId', $parent->id)
            ->where('language', $this->language)
            ->get()
            ->result();
    }

    public function imageCount($parent)
    {
        $this->setFilter();

        return $this->db
            ->from($this->imageTable)
            ->where('parentId', $parent->id)
            ->where('language', $this->language)
            ->count_all_results();
    }


    public function imageInsert($parent, $data = array())
    {
        $data['orderCondition'] = array('parentId' => $parent->id);
        $insert = $this->createData('insert', $data);
        $insert['parentId'] = $parent->id;
        $this->db->insert($this->imageTable, $insert);

        $insertId = $this->db->insert_id();

        if ($insertId > 0) {
            return $this->image($insertId);
        }

        return false;
    }


    public function imageUpdate($record, $data = array())
    {
        /** Query builder çakıştığı için değişkene aktarılması gerekmekte. */
        $update = $this->createData('update', $data);
        $this->db->where('id', $record->id)->update($this->imageTable, $update);

        if ($this->db->affected_rows() > 0) {
            return $this->find($record->id);
        }

        return false;
    }


    public function imageDelete($data)
    {
        $records = parent::callDelete($this->imageTable, $data, true);

        if (empty($records)){
            return false;
        }

        $paths = array();

        foreach ($this->imageColumns as $column => $options) {
            if ($options['type'] === 'image') {
                foreach ($options['process'] as $path => $opt) {
                    $paths[] = 'public/upload/'. $path .'/';
                }
            }
        }

        foreach ($records as $record){
            foreach ($paths as &$path) {
                $path .= $record->image;
            }

            $this->utils->deleteFile($paths);
        }

        return true;
    }


    public function imageOrder($ids)
    {
        return parent::callOrder($this->imageTable, $ids);
    }


    private function createData($for, $modelData = array())
    {
        $data = array(
            'language' => $this->language
        );

        foreach ($this->columns as $column => $options) {
            if (isset($options[$for]) && $options[$for] === true) {
                if ($options['type'] === 'slug') {
                    $data[$column] = $this->makeSlug();
                } elseif ($options['type'] === 'order') {
                    $data[$column] = $this->makeLastOrder(isset($modelData['orderCondition']) ? $modelData['orderCondition'] : array(), $column);
                } elseif ($options['type'] === 'datetime') {
                    $data[$column] = $this->date->set(isset($options['default']) ? $options['default'] : 'now')->mysqlDatetime();
                } elseif ($options['type'] === 'image') {
                    $data[$column] = $modelData['files'][$column]->name;
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