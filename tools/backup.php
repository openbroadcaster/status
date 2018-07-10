<?php

if(php_sapi_name()!=='cli')
{
  echo 'Backup can only be run from the command line.'.PHP_EOL;
  exit(1);
}

include(__DIR__.'/../config.php');

if(empty($config['backup']) || !is_array($config['backup']))
{
  echo 'No backup commands found in the config file.'.PHP_EOL;
  exit(1);
}

if(!isset($argv[1]))
{
  echo 'Usage: php backup.php COMMAND_INDEX
  
The command indices are found in config.php, $config[\'backup\'] array.'.PHP_EOL;
  exit(1);
}

if(!isset($config['backup'][$argv[1]]['command']))
{
  echo 'Backup command not found.'.PHP_EOL;
  exit(1);
}

$command = $config['backup'][$argv[1]]['command'];

exec($command, $output, $return_var);

// get our backup reports data to update
$reports_file = __DIR__.'/../reports/backup.json';
$reports = null;
if(file_exists($reports_file)) $reports = json_decode(file_get_contents(__DIR__.'/../reports/backup.json'), true);

// initial reports array if we don't have it already
if(!is_array($reports)) $reports = [];

// put together our report for this backup
$report = [
  'command'=>$command,
  'output'=>implode(PHP_EOL,$output),
  'status'=>$return_var,
  'last_run'=>time()
];

// add to our reports and write back to the json file
$reports[$argv[1]] = $report;
file_put_contents(__DIR__.'/../reports/backup.json', json_encode($reports));