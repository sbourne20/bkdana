
  (function() {    

    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "invest/json",
        "columns": [
          { "data": "Id" },
          { "data": "Id" },
          { "data": "Nama_pengguna" },
          { "data": function ( data, type, full, meta ) {return formatMoney(data.Jml_penawaran_pemberian_pinjaman,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": "Permintaan_jaminan", "sClass": "hidden-xs" },
          { "data": "Jml_penawaran_pemberian_pinjaman_disetujui", "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.Tgl_penawaran_pemberian_pinjaman);}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {
                    var status = data.pendanaan_status;
                    if (status=='approve') {
                      var ret='<span class="label label-success">'+status.ucfirst()+'</span>'; 
                    }else if (status=='pending') {
                      var ret='<span class="label label-info">'+status.ucfirst()+'</span>'; 
                    }else if (status=='reject') {
                      var ret='<span class="label label-danger">'+status.ucfirst()+'</span>'; 
                    }else{
                      var ret='<span class="label label-default">'+status.ucfirst()+'</span>'; 
                    }
                      return ret;                    
                  }, "sClass": "hidden-xs" 
          },
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="#myModal" onclick="view(\''+data.Id+'\')" data-toggle="modal"><i class="fa fa-eye"></i> View </a></li>'+
                          /*'<li><a href="javascript:;" onclick="verify_(\''+data.Id+'\')"><i class="fa fa-check"></i> Verifikasi</a></li>'+*/
                          '<li class="divider"></li>'+
                          '<li><a href="javascript:;" onclick="delete_(\''+data.Id+'\')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
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
              aTargets: [7]  // kolom #9 tidak bisa sorting
            }
          ]
    });

}).call(this);

var base_uri = window.location.href;
function view(id) {
    var pData = {
        'id' : id
      }; 

    $.ajax({
      url: base_uri + '/detail/' +id,
      type: 'POST',
      data: pData,
      success: function(data) {
        $('#sub_content').html('');
        $('#sub_content').html(data);
      },
      error: function(e) {
        //called when there is an error
        //console.log(e.message);
      }
    });
}

function delete_(id) {
  var c= confirm("Are you sure will delete this Pendanaan?");
  if(c==true){
    window.location.href = base_uri  + '/delete/' + id;
  }
  return false;
}

function verify_(id) {
  var c= confirm("Verifikasi pendanaan ini?");
  if(c==true){
    window.location.href = base_uri  + '/verify/' + id;
  }
  return false;
}
