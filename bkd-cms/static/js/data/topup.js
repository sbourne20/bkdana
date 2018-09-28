
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "top_up/json",
        "columns": [
          { "data": "id_top_up" },
          { "data": "kode_top_up" },
          { "data": "Nama_pengguna" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.jml_top_up,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.tgl_top_up);}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {
                    var top_status = data.status_top_up;
                    if (top_status=='approve') {
                      var ret='<span class="label label-success">'+top_status.ucfirst()+'</span>'; 
                    }else if (top_status=='reject') {
                      var ret='<span class="label label-danger">'+top_status.ucfirst()+'</span>'; 
                    }else if (top_status=='pending' && !data.payment_status) {
                      var ret='<span class="label label-warning">'+top_status.ucfirst()+'</span>'; 
                    }else if (data.payment_status=='settlement') {
                      var ret='<span class="label label-success">Success</span>';   
                    }else{
                      var ret = data.status_top_up;
                    }
                      return ret;                    
                  }, "sClass": "hidden-xs" 
          },
          { "data" : function ( data, type, full, meta ) {
              var top_status = data.status_top_up;
              if (top_status=='approve') {
                btn_approve = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-check"></i> Approve</a></li>';
              }else{
                btn_approve = '<li><a href="javascript:;" onclick="approve_('+data.id_top_up+')"><i class="fa fa-check"></i> Approve</a></li>';

              }
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="javascript:;" onclick="delete_user('+data.id_top_up+')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
                      '</ul>'+
                  '</div>';
              return btn_group;
              }, "class": "actions"
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
              aTargets: [2]  // kolom #1 tidak bisa sorting
            }
          ]            
    });

    //dataTable.fnSetColumnVis([0], false );  // hide kolom #0

}).call(this);

function delete_user(id) {
  var c= confirm("Are you sure will delete this Top Up?");
  if(c==true){
    window.location.href = baseURL + 'top_up/delete/' + id;
  }
  return false;
}
function approve_(id) {
  var c= confirm("Approve this Top Up?");
  if(c==true){
    window.location.href = baseURL + 'top_up/approve/' + id;
  }
  return false;
}