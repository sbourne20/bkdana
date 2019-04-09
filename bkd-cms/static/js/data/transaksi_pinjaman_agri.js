
  (function() {    

    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "transaksi_pinjaman_agri/json",
        "columns": [
          { "data": "Master_loan_id" },
          { "data": "Master_loan_id" },
          { "data": "Nama_pengguna" },
          { "data": "product_title", "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.Jml_permohonan_pinjaman,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.Amount,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.Jml_permohonan_pinjaman_disetujui,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return 'Rp ' + formatMoney(data.jml_kredit,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.Tgl_permohonan_pinjaman);}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {
                    var kredit = data.jml_kredit;
                    var pinjaman = data.Amount;
                    var kuota = (kredit/pinjaman)*100;
                    
                      return Math.round(kuota) + '%';                    
                  }, "sClass": "hidden-xs" 
          },
          { "data": function ( data, type, full, meta ) {
                    var loan_status = data.Master_loan_status;
                    if (loan_status=='approve') {
                      var ret='<span class="label label-primary">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='lunas') {
                      var ret='<span class="label label-success">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='reject') {
                      var ret='<span class="label label-danger">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='review') {
                      var ret='<span class="label label-warning">'+loan_status.ucfirst()+'</span>';
                    }else if (loan_status=='pending') {
                      var ret='<span class="label label-info">'+loan_status.ucfirst()+'</span>'; 
                    }else if (loan_status=='user') {
                      var ret='<span class="label label-info">User Approval</span>';
                    }else if (loan_status=='complete') {
                      var ret='<span class="label label-info">Telah didanai</span>';
                    }else{
                      var ret='<span class="label label-default">'+loan_status.ucfirst()+'</span>'; 
                    }
                      return ret;                    
                  }, "sClass": "hidden-xs" 
          },
          { "data" : function ( data, type, full, meta ) {
             var loan_status = data.Master_loan_status;
              if ( loan_status=='approve' || loan_status=='lunas' || loan_status=='expired' || loan_status=='pending'  || loan_status=='complete' || loan_status=='reject') {
                  var btn_aprrove = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-check"></i> Approve</a></li>';
                  var btn_reject = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-thumbs-down"></i> Reject</a></li>';
                  var btn_edit = '<li><a href="javascript:;" class="text-muted"><i class="fa fa-pencil"></i> Edit</a></li>';
              }else{
                  var btn_reject = '<li><a href="javascript:;" onclick="reject_(\''+data.Master_loan_id+'\')"><i class="fa fa-thumbs-down"></i> Reject</a></li>';
                  var btn_aprrove = '<li><a href="javascript:;" onclick="approve_(\''+data.Master_loan_id+'\')"><i class="fa fa-check"></i> Approve</a></li>';
                  var btn_edit = '<li><a href="javascript:;" onclick="edit_(\''+data.Master_loan_id+'\')"><i class="fa fa-pencil"></i> Edit</a></li>';
              }

              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="#myModal" onclick="view([\''+data.Master_loan_id+'\'])" data-toggle="modal"><i class="fa fa-eye"></i> View </a></li>'+
                          '<li><a href="javascript:;" onclick="detail_([\''+data.Master_loan_id+'\'])"><i class="fa fa-building-o"></i> Detail </a></li>'+
                          btn_aprrove +
                          btn_reject +
                          btn_edit +
                          '<li class="divider"></li>'+
                          '<li><a href="javascript:;" onclick="delete_(\''+data.Master_loan_id+'\')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
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
              aTargets: [8]  // kolom #9 tidak bisa sorting
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
      url: baseURL + 'transaksi_pinjaman_agri/detail/' +id,
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
  var c= confirm("Are you sure will delete this Transaction?");
  if(c==true){
    $.LoadingOverlay("show");
    window.location.href = base_uri  + '/delete/' + id;
  }
  return false;
}
function edit_(id) {
    $.LoadingOverlay("show");
    window.location.href = base_uri  + '/edit/' + id;
}
function detail_(id) {
    window.location.href = base_uri  + '/detail_transaksi/' + id;
}
function approve_(id) {
  var c= confirm("Approve this Transaction?");
  if(c==true){
    $.LoadingOverlay("show");
    window.location.href = base_uri  + '/approve/' + id;
  }
  return false;
}
function reject_(id) {
  var c= confirm("Reject this Transaction?");
  if(c==true){
    $.LoadingOverlay("show");
    window.location.href = base_uri  + '/reject/' + id;
  }
  return false;
}