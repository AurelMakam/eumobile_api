<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tb_bill extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tb_bill_model');
    } 

    /*
     * Listing of tb_bill
     */
    function index()
    {
        $data['tb_bill'] = $this->Tb_bill_model->get_all_tb_bill();
        
        $data['_view'] = 'tb_bill/index';
        $this->load->view('layouts/main',$data);
    }
}