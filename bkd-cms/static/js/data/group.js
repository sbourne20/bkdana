
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "group/json",
        "columns": [
          { "data": "id_mod_group" },
          { "data": "group_name" },
          { "data": function ( data, type, full, meta ) {
                    if( data.group_status =='1'){ 
                      ret='<span class="label label-success">Active</span>'; 
                    }else{
                      ret='<span class="label label-default">Not Active</span>'; 
                    }
                      return ret;
                  }, "sClass": "hidden-xs" 
          },
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="javascript:;" onclick="edit_user('+data.id_mod_group+')"><i class="fa fa-pencil"></i> Edit</a></li>'+                      
                          '<li><a href="javascript:;" onclick="delete_user('+data.id_mod_group+')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
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
              aTargets: [3]  // kolom #1 tidak bisa sorting
            }
          ]            
    });

    //dataTable.fnSetColumnVis([0], false );  // hide kolom #0

}).call(this);

function edit_user(id) {
    window.location.href = baseURL + 'group/edit/' + id;
    return false;
}
function delete_user(id) {
  var c= confirm("Are you sure will delete this Group?");
  if(c==true){
    window.location.href = baseURL + 'group/delete/' + id;
  }
  return false;
}