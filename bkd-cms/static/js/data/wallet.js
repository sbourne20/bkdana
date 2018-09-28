
  (function() {    
    var base = window.location.href;  // -> http://cms.blabla.com/user
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "wallet/json",
        "columns": [
          { "data": "Id" },
          { "data": "Nama_pengguna" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.Amount,0,'.',',');} },
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

function delete_user(id) {
  var c= confirm("Are you sure will delete this wallet?");
  if(c==true){
    window.location.href = baseURL + 'wallet/delete/' + id;
  }
  return false;
}