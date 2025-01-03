<?php 

defined('BASEPATH') OR exit('No direct script access allowed');




class Property_details extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'Propertydetails_model'
		));	
    }

    public function create(){ 			
        $this->form_validation->set_rules('propertyname',display('room_name'),'required|xss_clean');
        $this->form_validation->set_rules('propertyimg',display('capacity'),'required|xss_clean');
        $this->form_validation->set_rules('propertydescription',display('roomdescription'),'required|xss_clean');
        $data['intinfo']="";
        if ($this->form_validation->run()) { 

            $data['room_setting']   = (Object) $postData = array(
                'property_name'        => $this->input->post('propertyname', TRUE), 
                'property_drcpt' 	   => $this->input->post('propertydescription',TRUE), 
                'propertyactive' 	   => "1",
                );
                
            $this->permission->method('room_setting','create')->redirect();
            if ($this->propertydetails_model->create($postData)) { 
            $this->session->set_flashdata('message', display('save_successfully'));
            redirect('room_setting/room-list');
            }else { 
            echo Modules::run('template/layout', $data); 
            }   
        }
    }
}