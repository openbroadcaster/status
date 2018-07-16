<?php

$config = [];

// drives for S.M.A.R.T. health status
$config['smart'] = [
  'UUID-1'=>'System',
  'UUID-2'=>'Backup 1',
  'UUID-3'=>'Backup 2'
];

// partitions (mount points) for usage status
$config['usage'] = [
  '/'=>'System',
  '/mnt/backup1'=>'Backup 1',
  '/mnt/backup2'=>'Backup 2'
];

// backup commands
$config['backup'] = [

  'backup1db'=>[
    'description'=>'DB to Backup 1', 
    'command'=>'/usr/bin/mysqldump --single-transaction -u user -ppassword dbname > /mnt/backup1/db/obdb.sql'
  ],
  
  'backup2db'=>[
    'description'=>'DB to Backup 2', 
    'command'=>'/usr/bin/mysqldump --single-transaction -u user -ppassword dbname > /mnt/backup2/db/obdb.sql'
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