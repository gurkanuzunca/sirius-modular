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


    public function insert($parent, $data = array())
    {
        $reserved = $this->input->post('reserved');
        $order = $this->makeLastOrder(! empty($parent) ? array('parentId' => $parent->id) : 'parentId IS NULL');

        $this->db->insert($this->table, array(
            'parentId' => ! empty($parent) ? $parent->id : null,
            'title' => $this->input->post('title'),
            'slug' => $this->makeSlug(),
            'summary' => $this->input->post('summary'),
            'image' => $data['image']->name,
            'content' => $this->input->post('content'),
            'reserved' => ! empty($reserved) && $this->user->groupId === null ? $reserved : null,
            'metaTitle' => $this->input->post('metaTitle'),
            'metaDescription' => $this->input->post('metaDescription'),
            'metaKeywords' => $this->input->post('metaKeywords'),
            'order' => $order,
            'status' => $this->input->post('status'),
            'language' => $this->language,
            'createdAt' => $this->date->set()->mysqlDatetime(),
            'updatedAt' => $this->date->set()->mysqlDatetime()
        ));

        $insertId = $this->db->insert_id();

        if ($insertId > 0) {
            return $this->find($insertId);
        }

        return false;
    }


    public function update($record, $data = array())
    {
        $reserved = $this->input->post('reserved');

        $this->db
            ->where('id', $record->id)
            ->update($this->table, array(
                'title' => $this->input->post('title'),
                'slug' => $this->makeSlug(),
                'summary' => $this->input->post('summary'),
                'image' => $data['image']->name,
                'content' => $this->input->post('content'),
                'reserved' => ! empty($reserved) && $this->user->groupId === null ? $reserved : $record->reserved,
                'metaTitle' => $this->input->post('metaTitle'),
                'metaDescription' => $this->input->post('metaDescription'),
                'metaKeywords' => $this->input->post('metaKeywords'),
                'status' => $this->input->post('status'),
                'updatedAt' => $this->date->set()->mysqlDatetime()
            ));

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

        foreach ($records as $record){
            $this->utils->deleteFile([
                'public/upload/content/'. $record->image
            ]);
        }

        return true;
    }


    public function parents($id)
    {
        static $result = array();

        $record = $this->db->where('id', $id)->get($this->table)->row();

        if ($record) {
            array_unshift($result, array('title' => $record->title, 'url' => moduleUri('records', $record->id)));

            if ($record->parentId > 0) {
                $this->parents($record->parentId);
            }
        }

        return $result;
    }

}