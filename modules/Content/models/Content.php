<?php

use Sirius\Application\Model;

class Content extends Model
{

    private $table = 'contents';

    /**
     * Kayıt bulma
     *
     * @param $value
     * @param string $column
     * @return object
     */
    public function find($value, $column = 'id')
    {
        $result = $this->db
            ->from($this->table)
            ->where($column, $value)
            ->where('status', 'published')
            ->where('language', $this->language)
            ->get()
            ->row();

        if ($result) {
            $result->childs = $this->childs($result);
            $result->parent = $this->parent($result);
        }

        return $result;
    }

    /**
     * Kayıtları bulma
     *
     * @param array $values
     * @param string $column
     * @return array
     */
    public function findIn(array $values, $column = 'id')
    {
        $values = array_unique($values);

        $results = $this->db
            ->from($this->table)
            ->where_in($column, $values)
            ->where('status', 'published')
            ->where('language', $this->language)
            ->get()
            ->result();

        foreach ($results as $result) {
            $result->childs = $this->childs($result);
            $result->parent = $this->parent($result);
        }
    }

    /**
     * Slug'a göre kaydı bulur.
     *
     * @param $slug
     * @return object
     */
    public function findBySlug($slug)
    {
        return $this->find($slug, 'slug');
    }

    /**
     * Tüm kayıtları döndürür.
     *
     * @param array $paginate
     * @return array
     */
    public function all($paginate = [])
    {
        $this->setPaginate($paginate);

        return $this->db
            ->from($this->table)
            ->where('status', 'published')
            ->where('language', $this->language)
            ->order_by('order', 'asc')
            ->order_by('id', 'asc')
            ->get()
            ->result();
    }

    /**
     * Toplam kayıt sayısı.
     *
     * @return int
     */
    public function count()
    {
        return $this->db
            ->from($this->table)
            ->where('status', 'published')
            ->where('language', $this->language)
            ->count_all_results();
    }

    /**
     * Kaydın üst kaydını döndürür.
     *
     * @param $content
     * @return bool
     */
    public function parent($content)
    {
        if ($content->parentId > 0) {
            $result = $this->db
                ->from($this->table)
                ->where('id', $content->parentId)
                ->where('status', 'published')
                ->where('language', $this->language)
                ->get()
                ->row();

            if ($result) {
                $result->childs = $this->childs($result);
            }

            return $result;
        }

        return false;
    }

    /**
     * Kaydın alt kayıtlarını döndürür.
     *
     * @param $content
     * @return mixed
     */
    public function childs($content)
    {
        return $this->db
            ->from($this->table)
            ->where('parentId', $content->id)
            ->where('status', 'published')
            ->where('language', $this->language)
            ->get()
            ->result();
    }

    /**
     * Rezerve kaydı döndürür.
     *
     * @param string $name Rezerve kaydın tekil adı.
     * @param bool|false $childs
     * @return mixed
     */
    public function reserved($name, $childs = false)
    {
        $result = $this->db
            ->from($this->table)
            ->where('reserved', $name)
            ->where('status', 'published')
            ->where('language', $this->language)
            ->get()
            ->row();

        if ($childs === true) {
            $result->childs = $this->childs($result);
        }

        return $result;
    }

    /**
     * Rezerve kaydı alt kayıtları ile beraber döndürür.
     *
     * @param string $name Rezerve kaydı tekil adı.
     * @return mixed
     */
    public function reservedWithChilds($name)
    {
        return $this->reserved($name, true);
    }

}