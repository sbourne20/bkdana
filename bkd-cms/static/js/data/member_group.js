(function() {   
  var dataTable = $('#usergroup-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "member/json_group",
        "columns": [
          { "data": "id_user_group" },
          { "data": "user_group_name" },
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="javascript:;" onclick="delete_('+data.id_user_group+')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
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


function delete_(id) {
  var c= confirm("Anda yakin Hapus Member Group ini ?");
  if(c==true){
    window.location.href = baseURL + 'member/delete_group/' + id;
  }
  return false;
}