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
  
  public function get_usage()
  {
    include(__DIR__.'/../config.php');
    $filesystems = array_keys($config['usage']);

    exec('df -h', $output, $return_var);
    if($return_var!==0) return false;
    
    $usage = [];
    foreach($filesystems as $filesystem)
    {
      foreach($output as $line)
      {
        $parts = preg_split('/\s+/', $line);

        if($parts[0]==$filesystem)
        {
          $usage[$config['usage'][$parts[0]].' ('.$filesystem.')'] = [
            'size' => $parts[1],
            'used' => $parts[2],
            'avail' => $parts[3],
            'percent' => $parts[4]
          ];
        }
      }
    }
    
    if(empty($usage)) return false;
    return $usage;
  }
}