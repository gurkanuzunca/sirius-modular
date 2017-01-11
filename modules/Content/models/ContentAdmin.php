<?php

use Models\AdminModel;

class ContentAdmin extends AdminModel
{
    protected $table = 'contents';


    public function find($id)
    {
        return $this->db
            ->from($this->table)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function all($parent, $paginate = [])
    {
        $this->setFilter();
        $this->setPaginate($paginate);

        if (! empty($parent)) {
            $this->db->where('parentId', $parent->id);
        } else {
            $this->db->where('parentId IS NULL');
        }
        return $this->db
            ->select("{$this->table}.*, (SELECT COUNT(id) FROM {$this->table} child WHERE child.parentId = {$this->table}.id) childs", false)
            ->from($this->table)
            ->order_by('order', 'asc')
            ->where('language', $this->language)
            ->order_by("id", 'asc')
            ->get()
            ->result();
    }


    public function count($parent)
    {
        $this->setFilter();

        if (! empty($parent)) {
            $this->db->where('parentId', $parent->id);
        } else {
            $this->db->where('parentId IS NULL');
        }

        return $this->db
            ->from($this->table)
            ->where('language', $this->language)
            ->count_all_results();
    }


    public function insert($parent, $data = array())
    {
        $reserved = $this->input->post('reserved');
        $order = $this->makeLastOrder($this->table, ! empty($parent) ? array('parentId' => $parent->id) : 'parentId IS NULL');

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