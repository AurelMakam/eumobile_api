<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tb_partnerkey extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tb_partnerkey_model');
    } 

    /*
     * Listing of tb_partnerkey
     */
    function index()
    {
        $data['tb_partnerkey'] = $this->Tb_partnerkey_model->get_all_tb_partnerkey();
        
        $data['_view'] = 'tb_partnerkey/index';
        $this->load->view('layouts/main',$data);
    }
}
