
  (function() {    
    var member_id = $('#member_id').data('value');
    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        /*"bStateSave"  : true, // cache */ 
        "sAjaxSource" : baseURL + "bkdwallet/json_detail/"+member_id,
        "columns": [
          { "data": "Detail_wallet_id" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.Date_transaction);}, "sClass": "hidden-xs" },
          { "data": "kode_transaksi", "sClass": "hidden-xs"  },
          { "data": "Notes", "sClass": "hidden-xs"  },
          { "data" : function ( data, type, full, meta ) {
              if (data.tipe_dana == '1')
              {
                var ret = 'Kredit';
              }else{
                var ret = 'Debet';
              }
              return ret;
              }, "class": "actions"
          },
          { "data": function ( data, type, full, meta ) {return formatMoney(data.amount_detail,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return formatMoney(data.balance,0,'.',',');}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {return garing_date_format(data.Date_transaction);}, "sClass": "hidden-xs" } // di hide, hanya untuk filter periode end date
      
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
              aTargets: []  // kolom #9 tidak bisa sorting
            },
            {
                "targets": [ 7 ],
                "visible": false
            }
          ]        
    });

    /* Tipe Onchange */
    $('#from').on('change', function () {
        if (!this.value) {
            //$('#dynamic-table').DataTable().column(3).search(this.value).draw();
        } else {
            $('#dynamic-table').DataTable().column(1).search(this.value).draw();
        }
    });
     $('#to').on('change', function () {
        if (!this.value) {
            //$('#dynamic-table').DataTable().column(3).search(this.value).draw();
        } else {
            $('#dynamic-table').DataTable().column(6).search(this.value).draw();
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