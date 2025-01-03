<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Propertydetails_model extends CI_Model {
	
	private $table = 'property';
 
	public function create($data = array())
	{
		return $this->db->insert($this->table, $data);
	}
}