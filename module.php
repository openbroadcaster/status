<?php

class ServerStatusModule extends OBFModule
{

	public $name = 'Server Status v1.0';
	public $description = 'Server status information including disk usage, uptime, etc.';

	public function callbacks()
	{

	}

	public function install()
	{
      // add permissions data for this module
      $this->db->insert('users_permissions', [
        'category'=>'administration',
        'description'=>'server status module',
        'name'=>'server_status_module'
      ]);
      
      return true;
	}

	public function uninstall()
	{
      // remove permissions data for this module
      $this->db->where('name','server_status_module');
      $permission = $this->db->get_one('users_permissions');
      
      $this->db->where('permission_id',$permission['id']);
      $this->db->delete('users_permissions_to_groups');
      
      $this->db->where('id',$permission['id']);
      $this->db->delete('users_permissions');
      
      return true;
	}
}
