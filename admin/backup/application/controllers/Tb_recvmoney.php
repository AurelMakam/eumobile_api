<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tb_recvmoney extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tb_recvmoney_model');
    } 

    /*
     * Listing of tb_recvmoney
     */
    function index()
    {
        $data['tb_recvmoney'] = $this->Tb_recvmoney_model->get_all_tb_recvmoney();
        
        $data['_view'] = 'tb_recvmoney/index';
        $this->load->view('layouts/main',$data);
    }
}