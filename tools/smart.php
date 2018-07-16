<?php

if(php_sapi_name()!=='cli')
{
  echo 'S.M.A.R.T. check can only be run from the command line.'.PHP_EOL;
  exit(1);
}

include(__DIR__.'/../config.php');

if(empty($config['smart']) || !is_array($config['smart']))
{
  echo 'No S.M.A.R.T. drives found in the config file.'.PHP_EOL;
  exit(1);
}

$report = [
  'last_run'=>time(),
  'drives'=>[]
];

foreach($config['smart'] as $device=>$name)
{
  $output = [];
  exec('/usr/sbin/smartctl -a '.escapeshellarg($device), $output, $return_var);
  $report['drives'][$name]['status'] = $return_var;
  $report['drives'][$name]['report'] = implode(PHP_EOL,$output);
}

file_put_contents(__DIR__.'/../reports/smart.json', json_encode($report));