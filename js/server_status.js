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
      
      if(data.uptime === false) $('#server_status-uptime').html('<p>Error getting uptime.</p>');
      else
      {
        var $uptime = $('<div class="fieldrow"></div>');
        $uptime.append( $('<label>Uptime</label>') );
        $uptime.append( $('<span></span>').text(data.uptime) );
        $('#server_status-uptime').html($uptime);
      }
    
      if(data.load === false) $('#server_status-load').html('<p>Error getting load averages.</p>');
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

      if(data.smart === false) $('#server_status-smart').html('<p>Error getting S.M.A.R.T. status.</p>');
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
          $drive.append( $('<span></span>').append( $('<a href="javascript:OBModules.ServerStatus.viewReport('+drive_index+');"></a>').text(status.status === 0 ? 'OK ' : 'ERROR ') ));
          $drive.data('report',status.report);
          $drive.data('drive',drive);
          $('#server_status-smart').append($drive);
          drive_index++;
        });
      }
      
      if(data.usage === false) $('#server_status-usage').html('<p>Error getting disk usage.</p>');
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
    });
  }
  
  this.viewReport = function(drive_index)
  {
    // $('#server_status-smart_0').data('smart_report');
    OB.UI.openModalWindow('modules/server_status/smart_modal.html');
    
    $('#server_status-smart_report_drive').text( $('#server_status-smart_'+drive_index).data('drive') );
    $('#server_status-smart_report').text( $('#server_status-smart_'+drive_index).data('report') );
  }
}