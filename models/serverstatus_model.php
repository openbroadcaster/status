<?php

class ServerStatusModel extends OBFModel
{
  public function get_uptime()
  {
    exec('uptime -p', $output, $return_var);
    
    $output = implode(' ',$output);
    $output = trim(preg_replace('/^up/','',$output));
    
    if($return_var!==0) return false;
    return $output;
  }
  
  public function get_load()
  {
    exec('cat /proc/loadavg', $output, $return_var);
    $averages = explode(' ',$output[0]);
    
    if($return_var!==0 || count($averages)<3) return false;
    return array_slice($averages,0,3);
  }
  
  public function get_smart()
  {
    $smart = file_get_contents(__DIR__.'/../reports/smart.json');

    if(!$smart) return false;
    return json_decode($smart);
  }
}