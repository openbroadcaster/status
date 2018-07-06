<?php

$config = [];

// drives for S.M.A.R.T. health status
$config['smart'] = [
  '/dev/sdc'=>'System',
  '/dev/sda'=>'Backup 1',
  '/dev/sdb'=>'Backup 2'
];

// partitions for usage status
$config['usage'] = [
  '/dev/sdc1'=>'System',
  '/dev/sda1'=>'Backup 1',
  '/dev/sdb1'=>'Backup 2'
];

// drives for backup status
$config['backup'] = [
  '/mnt/backup1'=>'Backup 1',
  '/mnt/backup2'=>'Backup 2'
];