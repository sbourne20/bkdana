
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "redeem/json",
        "columns": [
          { "data": "mod_redeem_id" },
          { "data": "redeem_kode" },
          { "data": "Nama_pengguna" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.redeem_amount,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": "redeem_nomor_rekening", "sClass": "hidden-xs" },
          { "data": "redeem_nama_bank", "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.redeem_date);}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {
                    var top_status = data.redeem_status;
                    if (top_status=='approve') {
                      var ret='<span class="label label-success">'+top_status.ucfirst()+'</span>'; 
                    }else if (top_status=='reject') {
                      var ret='<span class="label label-danger">'+top_status.ucfirst()+'</span>'; 
                    }else if (top_status=='pending') {
                      var ret='<span class="label label-warning">'+top_status.ucfirst()+'</span>'; 
                    }else{
                      var ret='<span class="label label-default">'+top_status.ucfirst()+'</span>'; 
                    }
                      return ret;                    
                  }, "sClass": "hidden-xs" 
          },
          { "data" : function ( data, type, full, meta ) {
              var top_status = data.redeem_status;

              var btn_aprrove = '<li><a href="javascript:;" onclick="approve_('+data.mod_redeem_id+')"><i class="fa fa-check"></i> Approve</a></li>';
              var btn_reject  = '<li><a href="javascript:;" onclick="reject_('+data.mod_redeem_id+')"><i class="fa fa-thumbs-down"></i> reject</a></li>';
              
              if (top_status=='approve') {
                  var btn_aprrove = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-check"></i> Approve</a></li>';
                  var btn_reject = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-thumbs-down"></i> Reject</a></li>';
              }else if (top_status=='reject') {
                  var btn_reject = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-thumbs-down"></i> Reject</a></li>';
              }else{}

              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          btn_aprrove+
                          btn_reject+
                          '<li><a href="javascript:;" onclick="delete_user('+data.mod_redeem_id+')"><i class="fa fa-close"></i> Delete</a></li>'+
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
              aTargets: [8]  // kolom #1 tidak bisa sorting
            }
          ]            
    });

    //dataTable.fnSetColumnVis([0], false );  // hide kolom #0

}).call(this);

function delete_user(id) {
  var c= confirm("Are you sure will delete this Redeem?");
  if(c==true){
    window.location.href = baseURL + 'redeem/delete/' + id;
  }
  return false;
}
function approve_(id) {
  var c= confirm("Approve this Redeem?");
  if(c==true){
    window.location.href = baseURL + 'redeem/approve/' + id;
  }
  return false;
}
function reject_(id) {
  var c= confirm("Reject this Redeem?");
  if(c==true){
    window.location.href = baseURL + 'redeem/reject/' + id;
  }
  return false;
}