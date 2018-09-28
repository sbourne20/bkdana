
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "platform/json",
        "columns": [
          { "data": "ltp_id" },
          { "data": "ltp_Master_loan_id" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.ltp_platform_fee,0,'.',',');} },
          { "data": function ( data, type, full, meta ) {
                    var loan_status = data.Master_loan_status;
                    if (loan_status=='approve') {
                      var ret='<span class="label label-primary">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='lunas') {
                      var ret='<span class="label label-success">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='reject') {
                      var ret='<span class="label label-danger">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='review') {
                      var ret='<span class="label label-warning">'+loan_status.ucfirst()+'</span>';
                    }else if (loan_status=='complete') {
                      var ret='<span class="label label-info">Telah didanai</span>'; 
                    }else{
                      var ret=''; 
                    }
                      return ret;                    
                  } 
          }
        ],  
      "lengthMenu": [[20, 50, 100, 200], [20, 50, 100, 200]],
      "order": [[ 0, "desc" ]],  // default order
      "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:eq(0)", nRow).html(iDisplayIndex +1);
               return nRow;
            },
      "sPaginationType": "full_numbers",
       aoColumnDefs: [
            {
              bSortable: false,
              aTargets: []  // kolom #1 tidak bisa sorting
            }
          ]            
    });

    //dataTable.fnSetColumnVis([0], false );  // hide kolom #0

}).call(this);

