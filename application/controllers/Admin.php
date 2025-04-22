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

        // Fetch existing estates from the database
        $data['estates'] = $this->admin_model->get_estates();

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

    public function download_estate($id)
    {
        // Fetch the estate details from the database
        $estate = $this->admin_model->get_estate_by_id($id);

        if ($estate) {
            // Split the image names into an array
            $image_names = explode(',', $estate['image_names']);

            // Loop through each image and serve it for download
            foreach ($image_names as $image_name) {
                $file_path = './uploads/' . $image_name;
                if (file_exists($file_path)) {
                    // Set headers to force download
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file_path));
                    flush(); // Flush system output buffer
                    readfile($file_path);
                    exit; // Exit after the first file download
                } else {
                    // Handle the case where the file does not exist
                    $this->session->set_flashdata('error_msg', 'File not found: ' . $image_name);
                    redirect('admin/add_estates');
                }
            }
        } else {
            // Handle the case where the estate does not exist
            $this->session->set_flashdata('error_msg', 'Estate not found.');
            redirect('admin/add_estates');
        }
    }
}
