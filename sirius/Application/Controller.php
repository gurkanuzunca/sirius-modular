<?php

namespace Sirius\Application;


abstract class Controller extends \MX_Controller
{
    /**
     * Form Validate.
     *
     * @param array $rules
     * @return bool
     */
    public function validate($rules = array())
    {
        foreach ($rules as $name => $rule) {
            $this->form_validation->set_rules($name, $rule[1], $rule[0]);
        }

        if ($this->form_validation->run() === false) {
            $this->alert->set('error', $this->form_validation->errors());

            return false;
        }

        return true;
    }

    /**
     * Sayfalama.
     *
     * @param $count
     * @param int $limit
     * @param null $url
     * @return array
     */
    public function paginate($count, $limit = 20, $url = null)
    {
        $this->load->library('pagination');
        $this->pagination->initialize([
            'base_url' => empty($url) ? current_url() : $url,
            'total_rows' => $count
        ]);

        return [
            'limit' => $limit,
            'offset' => $this->pagination->offset,
            'pagination' => $this->pagination->create_links()
        ];
    }


    public function json($data)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    /**
     * View dosyasını layout ile birlikte yükler.
     *
     * @param $file
     * @param $data
     */
    public function render($file, $data = array())
    {
        if (is_array($file)) {
            $file = implode('/', $file);
        }

        $data['view'] = $file;
        $this->load->view('home/master', $data);
    }


    /**
     * Modül verilerini çeker.
     *
     * @param $name
     * @return mixed
     */
    public function getModule($name)
    {
        $module = $this->db
            ->from('modules')
            ->where('name', $name)
            ->get()
            ->row();

        if ($module) {
            $arguments = $this->db
                ->from('module_arguments')
                ->where('module', $module->name)
                ->where('language', $this->language)
                ->get()
                ->result();

            $module->arguments = new \stdClass();
            foreach ($arguments as $argument) {
                $module->arguments->{$argument->name} = $argument->value;
            }
        }

        return $module;
    }


    protected function setMeta($record, $type = null)
    {
        $this->stack->set('options.metaTitle', !empty($record->metaTitle) ? $record->metaTitle : $record->title);
        $this->stack->set('options.metaDescription', $record->metaDescription);
        $this->stack->set('options.metaKeywords', $record->metaKeywords);
        $this->stack->set('options.ogTitle', $record->title);

        if (! empty($type)) {
            $this->stack->set('options.ogType', $type);
        }

        if (! empty($record->summary)) {
            $this->stack->set('options.ogDescription', $record->summary);
        }

        if (! empty($record->image)) {
            $this->stack->set('options.ogImage', getImage($record->image, 'content'));
        }


    }

} 