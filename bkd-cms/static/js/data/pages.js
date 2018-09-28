
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic_table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : base + "/json",
        "columns": [
          { "data": "p_id" },
          { "data": "p_title" },
          { "data" : function ( data, type, full, meta ) {
            if (data.p_images != ''){
              var btn_group ='<img width="80" alt="" src="'+image_url+'pages/'+data.p_id+'/'+data.p_images+'" >';
            }else{
              var btn_group = '';
            }
              return btn_group;
              }, "class": "actions"
          },
          { "data": function ( data, type, full, meta ) {
                    if( data.p_status =='1'){ 
                      ret='<span class="label label-success">Active</span>'; 
                    }else{
                      ret='<span class="label label-default">Inactive</span>'; 
                    }
                      return ret;
                  }, "sClass": "hidden-xs" 
          },
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="javascript:;" onclick="edit_channel('+data.p_id+')"><i class="fa fa-pencil"></i> Edit</a></li>'+
                      '</ul>'+
                  '</div>';
              return btn_group;
              }, "class": "actions"
          }
        ],  
      "lengthMenu": [[50, 100, 200], [50, 100, 200]],
      "order": [[ 0, "desc" ]],  // default order
      "sPaginationType": "full_numbers",
          aoColumnDefs: [
            {
              bSortable: false,
              aTargets: [4]  // kolom #1 tidak bisa sorting
            },
            { "width": "8%", "targets": [0] }
          ],
          "fnRowCallback" : function(nRow, aData, iDisplayIndex){
              $("td:eq(0)", nRow).html(iDisplayIndex +1);
             return nRow;
          }
    });

    // dataTable.fnSetColumnVis([0], false );  // hide kolom #0

}).call(this);

var base = window.location.href;

function edit_channel(id) {
    window.location.href = base + '/edit/' + id;
    return false;
}
function delete_channel(id) {
  var c= confirm("Anda akan menghapus Page ini secara permanen. Teruskan?");
  if(c==true){
    window.location.href = base + '/delete/' + id;
  }
  return false;
}