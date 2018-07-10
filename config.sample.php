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

  'backup1db'=>[
    'description'=>'DB to Backup 1', 
    'command'=>'/usr/bin/mysqldump -u user -ppassword dbname > /mnt/backup1/db/obdb.sql'
  ],
  
  'backup2db'=>[
    'description'=>'DB to Backup 2', 
    'command'=>'usr/bin/mysqldump -u user -ppassword dbname > /mnt/backup2/db/obdb.sql'
  ],
  
  'backup1media'=>[
    'description'=>'Media to Backup 1',
    'command'=>'/usr/bin/rsync -a /home/media/ /mnt/backup1/media/'
  ],
  
  'backup2media'=>[
    'description'=>'Media to Backup 2',
    'command'=>'/usr/bin/rsync -a /home/media/ /mnt/backup2/media/'
  ],
    
  'backup1server'=>[
    'description'=>'OB Server to Backup 1',
    'command'=>'/usr/bin/rsync -a /var/www/observer/ /mnt/backup1/server/'
  ],
  
  'backup2server'=>[
    'description'=>'OB Server to Backup 2',
    'command'=>'/usr/bin/rsync -a /var/www/observer/ /mnt/backup2/server/'
  ]
  
];