<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tb_timeout_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get tb_timeout by col_partner_id
     */
    function get_tb_timeout($col_partner_id)
    {
        return $this->db->get_where('tb_timeout',array('col_partner_id'=>$col_partner_id))->row_array();
    }
        
    /*
     * Get all tb_timeout
     */
    function get_all_tb_timeout()
    {
        $this->db->order_by('col_partner_id', 'desc');
        return $this->db->get('tb_timeout')->result_array();
    }
        
    /*
     * function to add new tb_timeout
     */
    function add_tb_timeout($params)
    {
        $this->db->insert('tb_timeout',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update tb_timeout
     */
    function update_tb_timeout($col_partner_id,$params)
    {
        $this->db->where('col_partner_id',$col_partner_id);
        return $this->db->update('tb_timeout',$params);
    }
    
    /*
     * function to delete tb_timeout
     */
    function delete_tb_timeout($col_partner_id)
    {
        return $this->db->delete('tb_timeout',array('col_partner_id'=>$col_partner_id));
    }
}
