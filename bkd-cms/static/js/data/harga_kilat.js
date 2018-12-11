
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "harga_pinjaman_kilat/json",
        "columns": [
          { "data": "h_id" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.h_harga,0,'.',',');} },
          { "data": function ( data, type, full, meta ) {
              return data.tenor  ;
            }
          },
          { "data": function ( data, type, full, meta ) {
              if (data.h_status=='1') {
                var ret='<span class="label label-primary">Active</span>'; 
              }else{
                var ret='<span class="label label-default">Not Active</span>';                 
              }
              return ret;
            }
          },          
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="javascript:;" onclick="edit_user('+data.h_id+')"><i class="fa fa-pencil"></i> Edit</a></li>'+                      
                          '<li><a href="javascript:;" onclick="delete_user('+data.h_id+')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
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
              aTargets: [4]  // kolom tidak bisa sorting
            }
          ]            
    });

}).call(this);

function edit_user(id) {
    window.location.href = baseURL + 'harga_pinjaman_kilat/edit/' + id;
    return false;
}
function delete_user(id) {
  var c= confirm("Are you sure will delete this data?");
  if(c==true){
    window.location.href = baseURL + 'harga_pinjaman_kilat/delete/' + id;
  }
  return false;
}