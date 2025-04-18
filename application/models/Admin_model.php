<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct(); // Call the CI_Model constructor
        $this->load->database(); // Load the database library
    }

    public function add_estate($title, $description, $image_names)
    {
        $data = array(
            'title' => $title,
            'description' => $description,
            'image_names' => implode(',', $image_names) // Store image names as a comma-separated string
        );

        return $this->db->insert('estates', $data);
    }
}
