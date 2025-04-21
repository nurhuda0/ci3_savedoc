<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->library('session'); // Load the session library
        $this->load->helper('url'); // Load the URL helper
    }

    public function add_estates()
    {
        $data['page_title'] = "Upload your Images";
        $data['main_content'] = 'admin/add_estates';

        if ($this->input->post() !== FALSE) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title', 'Career Title', 'trim|required');

            if ($this->form_validation->run() !== FALSE) {
                $title = $this->input->post('title');
                $description = $this->input->post('description');

                if (!empty($_FILES['images']['name'][0])) {
                    $image_names = $this->upload_files('./uploads/', $title, $_FILES['images']);
                    if ($image_names === FALSE) {
                        $data['error'] = $this->upload->display_errors('<div class="alert alert-danger">', '</div>');
                    }
                }

                if (!isset($data['error'])) {
                    $this->admin_model->add_estate($title, $description, $image_names);
                    $this->session->set_flashdata('suc_msg', 'New real estate added successfully');
                    redirect('admin/add_estates');
                }
            }
        }

        $data['suc_msg'] = $this->session->flashdata('suc_msg');
        $this->load->view('layout_admin', $data);
    }

    private function upload_files($path, $title, $files)
    {
        $config = array(
            'upload_path'   => $path,
            'allowed_types' => 'jpg|gif|png',
            'overwrite'     => 1,
        );

        $this->load->library('upload', $config);
        $images = array();

        foreach ($files['name'] as $key => $image) {
            $_FILES['images[]']['name'] = $files['name'][$key];
            $_FILES['images[]']['type'] = $files['type'][$key];
            $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images[]']['error'] = $files['error'][$key];
            $_FILES['images[]']['size'] = $files['size'][$key];

            $fileName = $title . '_' . $image;
            $images[] = $fileName;
            $config['file_name'] = $fileName;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('images[]')) {
                return false;
            }
        }

        return $images;
    }
}
