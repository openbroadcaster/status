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
    if(!isset($config['usage']) || !is_array($config['usage'])) return false;
    
    $mountpoints = array_keys($config['usage']);

    exec('df -h', $output, $return_var);
    if($return_var!==0) return false;
    
    $usage = [];
    foreach($mountpoints as $mountpoint)
    {
      foreach($output as $line)
      {
        $parts = preg_split('/\s+/', $line);

        if($parts[5]==$mountpoint)
        {
          $usage[$config['usage'][$parts[5]]] = [
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
  
  public function get_backup()
  {
    include(__DIR__.'/../config.php');
    if(!isset($config['backup']) || !is_array($config['backup'])) return false;
    
    $return = [];
    $backups = array_keys($config['backup']);
    
    // get our report data
    $reports_file = __DIR__.'/../reports/backup.json';
    if(!file_exists($reports_file)) return false;
    $reports = json_decode(file_get_contents($reports_file),true);
    if(!is_array($reports)) return false;
    
    // get report for each backup
    foreach($backups as $backup)
    {
      if(isset($reports[$backup]))
      {
        $return[$backup] = $reports[$backup];
        $return[$backup]['description'] = $config['backup'][$backup]['description'];
      }
    }
    
    // return our backup status data
    if(empty($return)) return false;
    else return $return;
  }
}