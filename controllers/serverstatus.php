<?php

class ServerStatus extends OBFController
{

  public function __construct()
  {
    parent::__construct();
    $this->user->require_permission('server_status_module');
    $this->model = $this->load->model('ServerStatus');
  }
  
  public function get_all()
  {
    $status = [];
    $status['uptime'] = $this->model->get_uptime();
    $status['load'] = $this->model->get_load();
    $status['smart'] = $this->model->get_smart();
    $status['usage'] = $this->model->get_usage();
    
    return array(true,'Server Status',$status);
  }

}
