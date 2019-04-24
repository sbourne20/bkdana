
  (function() {    

    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "pendana/json",
        "columns": [
          { "data": "id_mod_user_member" },
          { "data": "Nama_pengguna" },
          { "data": "mum_email", "sClass": "hidden-xs"  },
          { "data": "Mobileno", "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.Tgl_record);}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {
              return 'Grade: ' + data.peringkat_pengguna + '<br>Skor: '+data.skoring;
            } 
          },
          { "data": function ( data, type, full, meta ) {
                    var status = data.mum_status;
                    if (status=='1') {
                      var ret='<span class="label label-success">Active</span>'; 
                    }else if (status=='2') {
                      var ret='<span class="label label-warning">Pending</span>'; 
                    }else{
                      var ret='<span class="label label-default">Not Active</span>'; 
                    }
                      return ret;                    
                  }
          },
          { "data" : function ( data, type, full, meta ) {

            var status = data.mum_status;
            if (status=='1') {
                var btn_activate = '<li><a href="javascript:;" onclick="deactivate_('+data.id_mod_user_member+')"><i class="fa fa-check"></i> Deactivate</a></li>';
            }else{
                var btn_activate = '<li><a href="javascript:;" onclick="activate_('+data.id_mod_user_member+')"><i class="fa fa-check"></i> Activate</a></li>';

            }

            var id_pendana_internal = $('#pendana_internal').val();
            if (data.id_mod_user_member == id_pendana_internal)
            {
              var btn_delete = '';
            }else{
              var btn_delete = '<li><a href="javascript:;" onclick="delete_('+data.id_mod_user_member+')"><i class="fa fa-trash-o"></i> Delete</a></li>';
            }
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="#myModal" onclick="view('+data.id_mod_user_member+')" data-toggle="modal"><i class="fa fa-eye"></i> Detail </a></li>'+
                          /*'<li><a href="#myModal" onclick="view('+data.id_mod_user_member+')" data-toggle="modal"><i class="fa fa-money"></i> Wallet </a></li>'+*/
                          btn_activate+
                          '<li><a href="javascript:;" onclick="edit_('+data.id_mod_user_member+')"><i class="fa fa-pencil"></i> Edit Grade </a></li>'+
                          '<li><a href="javascript:;" onclick="editprofil_('+data.id_mod_user_member+')"><i class="fa fa-pencil"></i> Edit Profil</a></li>'+
                          '<li class="divider"></li>'+
                          btn_delete+
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

function delete_(id) {
  var c= confirm("Are you sure will delete this member?");
  if(c==true){
    window.location.href = base_uri  + '/delete/' + id;
  }
  return false;
}
function activate_(id) {
  var c= confirm("Are you sure will Activate this member?");
  if(c==true){
    window.location.href = base_uri  + '/activate/' + id;
  }
  return false;
}
function deactivate_(id) {
  var c= confirm("Are you sure will Deactivate this member?");
  if(c==true){
    window.location.href = base_uri  + '/deactivate/' + id;
  }
  return false;
}
function edit_(id) {
    window.location.href = base_uri  + '/edit/' + id;  
}
function editprofil_(id) {
    window.location.href =  'peminjam/editprofil/' + id;  
}