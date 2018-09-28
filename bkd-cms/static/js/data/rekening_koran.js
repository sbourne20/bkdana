
  (function() {    

    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "rekening_koran/json",
        "columns": [
          { "data": "id_mod_user_member" },
          { "data": "Nama_pengguna" },
          { "data": "mum_email" },
          { "data": function ( data, type, full, meta ) {
                    var type = data.mum_type;
                    if (type=='1') {
                      var ret='<span class="label label-primary">Peminjam</span>'; 
                    }else if (type=='2') {
                      var ret='<span class="label label-success">Pendana</span>'; 
                    }else{
                      var ret=type; 
                    }
                      return ret;                    
                  }
          },
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" onclick="detail_('+data.id_mod_user_member+')" class="btn btn-primary dropdown-toggle btn-sm" type="button"><i class="fa fa-eye"></i> Rekening Koran </button>'+
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
              aTargets: [3]  // kolom #9 tidak bisa sorting
            }
          ]        
    });

    /* Tipe Onchange */
    $('#select_tipe').on('change', function () {
        if (!this.value) {
            $('#dynamic-table').DataTable().column(3).search(this.value).draw();
        } else {
            $('#dynamic-table').DataTable().column(3).search(this.value).draw();
        }
    });

}).call(this);

var base_uri = window.location.href;
function view(id) {
    var pData = {
        'id' : id
      }; 

    $.ajax({
      url: base_uri + '/detail',
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

function detail_(id) {
  window.location.href = base_uri  + '/detail/' + id;
}