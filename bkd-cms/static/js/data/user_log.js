
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "user/json_log",
        "columns": [
          { "data": "id_system_user_log" },
          { "data": "ddate" },
          { "data": "time", "sClass": "hidden-xs"  },
          { "data": "username", "sClass": "hidden-xs" },
          { "data": "activities", "sClass": "hidden-xs" }
        ],  
      "lengthMenu": [[100, 200, 300], [100, 200, 300]],
      "order": [[ 0, "desc" ]],  // default order
      "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:eq(0)", nRow).html(iDisplayIndex +1);
               return nRow;
            },
      "sPaginationType": "full_numbers",        
    });

    //dataTable.fnSetColumnVis([0], false );  // hide kolom #0

}).call(this);

function edit_user(id) {
    window.location.href = baseURL + 'user/edit/' + id;
    return false;
}
function delete_user(id) {
  var c= confirm("Anda yakin Hapus ?");
  if(c==true){
    window.location.href = baseURL + 'user/delete/' + id;
  }
  return false;
}