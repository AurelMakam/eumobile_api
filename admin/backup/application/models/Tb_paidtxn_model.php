<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tb_paidtxn_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get tb_paidtxn by col_id
     */
    function get_tb_paidtxn($col_id)
    {
        return $this->db->get_where('tb_paidtxn',array('col_id'=>$col_id))->row_array();
    }
        
    /*
     * Get all tb_paidtxn
     */
    function get_all_tb_paidtxn()
    {
        $this->db->order_by('col_id', 'desc');
        return $this->db->get('tb_paidtxn')->result_array();
    }
        
    /*
     * function to add new tb_paidtxn
     */
    function add_tb_paidtxn($params)
    {
        $this->db->insert('tb_paidtxn',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update tb_paidtxn
     */
    function update_tb_paidtxn($col_id,$params)
    {
        $this->db->where('col_id',$col_id);
        return $this->db->update('tb_paidtxn',$params);
    }
    
    /*
     * function to delete tb_paidtxn
     */
    function delete_tb_paidtxn($col_id)
    {
        return $this->db->delete('tb_paidtxn',array('col_id'=>$col_id));
    }
}