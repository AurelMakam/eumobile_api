<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tb_partner_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get tb_partner by col_id
     */
    function get_tb_partner($col_id)
    {
        return $this->db->get_where('tb_partner',array('col_id'=>$col_id))->row_array();
    }
    
    /*
     * Get all tb_partner count
     */
    function get_all_tb_partner_count()
    {
        $this->db->from('tb_partner');
        return $this->db->count_all_results();
    }
        
    /*
     * Get all tb_partner
     */
    function get_all_tb_partner($params = array())
    {
        $this->db->order_by('col_id', 'desc');
        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }
        return $this->db->get('tb_partner')->result_array();
    }
        
    /*
     * function to add new tb_partner
     */
    function add_tb_partner($params)
    {
        $this->db->insert('tb_partner',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update tb_partner
     */
    function update_tb_partner($col_id,$params)
    {
        $this->db->where('col_id',$col_id);
        return $this->db->update('tb_partner',$params);
    }
    
    /*
     * function to delete tb_partner
     */
    function delete_tb_partner($col_id)
    {
        return $this->db->delete('tb_partner',array('col_id'=>$col_id));
    }
}
