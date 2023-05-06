OBModules.ServerStatus = new function()
{

  this.init = function()
  {
    OB.Callbacks.add('ready',0,OBModules.ServerStatus.initMenu);
    if(!document.getElementById('obmodules-chart-js')) $('head').append('<script id="obmodules-chart-js" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>');
  }

  this.initMenu = function()
  {
    OB.UI.addSubMenuItem('admin','Server Status','server_status',OBModules.ServerStatus.open,150,'server_status_module');
  }
  
  this.open = function()
  {
    OB.UI.replaceMain('modules/server_status/server_status.html');
    
    OB.API.post('serverstatus', 'get_all', {}, function(response)
    {
      var data = response.data;
      
      if(data.uptime === false) $('#server_status-uptime').html('<p>Uptime not available.</p>');
      else
      {
        var $uptime = $('<div class="fieldrow"></div>');
        $uptime.append( $('<label>Uptime</label>') );
        $uptime.append( $('<span></span>').text(data.uptime) );
        $('#server_status-uptime').html($uptime);
      }
    
      if(data.load === false) $('#server_status-load').html('<p>Load averages not available.</p>');
      else
      {
        var $load1 = $('<div class="fieldrow"></div>');
        $load1.append( $('<label>1min Load Avg</label>') );
        $load1.append( $('<span></span>').text(data.load[0]) );
        $('#server_status-load').html($load1);
        
        var $load2 = $('<div class="fieldrow"></div>');
        $load2.append( $('<label>5min Load Avg</label>') );
        $load2.append( $('<span></span>').text(data.load[1]) );
        $('#server_status-load').append($load2);
        
        var $load3 = $('<div class="fieldrow"></div>');
        $load3.append( $('<label>15min Load Avg</label>') );
        $load3.append( $('<span></span>').text(data.load[2]) );
        $('#server_status-load').append($load3);
      }

      if(data.smart === false) $('#server_status-smart').html('<p>S.M.A.R.T. status not available.</p>');
      else
      {
        var $lastrun = $('<div class="fieldrow"></div>');
        $lastrun.append( $('<label>Last Updated</label>') );
        $lastrun.append( $('<span></span>').text(format_timestamp(data.smart.last_run)) );
        $('#server_status-smart').html($lastrun);
        
        var drive_index = 0;
        $.each(data.smart.drives, function(drive,status) {
          var $drive = $('<div id="server_status-smart_'+drive_index+'" class="fieldrow"></div>');
          $drive.append( $('<label></label>').text(drive) );
          $drive.append( $('<span></span>').append( $('<a href="javascript:OBModules.ServerStatus.viewSmartReport('+drive_index+');"></a>').text(status.status === 0 ? 'OK ' : 'ERROR ') ));
          $drive.data('report',status.report);
          $drive.data('drive',drive);
          $('#server_status-smart').append($drive);
          drive_index++;
        });
      }
      
      if(data.usage === false) $('#server_status-usage').html('<p>Disk usage not available.</p>');
      else
      {
        $('#server_status-usage').html('');
        $.each(data.usage, function(drive,usage) {
          var $drive = $('<div class="fieldrow"></div>');
          $drive.append( $('<label></label>').text(drive) );
          $drive.append( $('<span></span>').text( usage.used+' of '+usage.size+' ('+usage.percent+')' ) );
          $('#server_status-usage').append($drive);
        });
      }
      

      if(data.backup === false) $('#server_status-backup').html('<p>Backup status not available.</p>');
      else
      {
        $('#server_status-backup').html('');
        var backup_index = 0;
        $.each(data.backup, function(drive,report) {
          var $backup = $('<div id="server_status-backup_'+backup_index+'" class="fieldrow"></div>');
          $backup.append( $('<label></label>').text(report.description) );
          $backup.append( $('<span></span>').append( $('<a href="javascript:OBModules.ServerStatus.viewBackupReport('+backup_index+');"></a>').text(report.status === 0 ? 'OK ' : 'ERROR ') ).append('('+format_timestamp(report.last_run)+')') );
          $backup.data('report',report.output ? report.output : 'No output from backup command.');
          $backup.data('description',report.description);
          $('#server_status-backup').append($backup);
          backup_index++;
        });
      }
    });
  }
  
  this.viewSmartReport = function(drive_index)
  {
    OB.UI.openModalWindow('modules/server_status/report_modal.html');
    $('#server_status-report_heading').text( $('#server_status-smart_'+drive_index).data('drive') );
    $('#server_status-report_content').text( $('#server_status-smart_'+drive_index).data('report') );
  }
  
  this.viewBackupReport = function(backup_index)
  {
    OB.UI.openModalWindow('modules/server_status/report_modal.html');
    $('#server_status-report_heading').text( $('#server_status-backup_'+backup_index).data('description') );
    $('#server_status-report_content').text( $('#server_status-backup_'+backup_index).data('report') );
  }
}