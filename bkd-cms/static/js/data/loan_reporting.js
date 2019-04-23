
  (function() {  
    

    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "loan_reporting/json",
        "dom"         : 'Bfrtip',
        "buttons"    : [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
        "columns": [

          { "data": null },
          { "data": "Master_loan_id" },
          { "data": "Nama_pengguna" },
          { "data": "Type_of_business" },
          { "data": "Agent_Code"},
          { "data": function ( data, type, full, meta ){return garing_date_format(data.tgl_disburse);}, "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ){return garing_date_format(data.ltp_tgl_jatuh_tempo);}, "sClass": "hidden-xs"},
          
          { "data" : function ( data, type, full, meta ) {
              return data.ltp_product_loan_term + data.type_of_interest_rate_name;
            }, "sClass": "hidden-xs"
          },

          { "data": "ltp_total_pinjaman" },
          { "data": "Total_loan_outstanding" },
          { "data": "Interest_rate_type"},
          //{ "data": "Max_tgl" },
          // { "data": "tgl_pembayaran" },
          { "data" : "ltp_bunga_pinjaman"},
          { "data": "Max_tgl" },

          { "data" : function ( data, type, full, meta ) {
              if(data.daysbetween < '0')
                {
                  return data.daysbetween = '0';
                }else{
                  return data.daysbetween
                };
            }, "sClass": "hidden-xs"
          },
          
          //    if({ "data": "daysbetween" }<0){
          //     "data": "0"
          //    },
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

    $('#from').on('change', function () {
        if (!this.value) {
            //$('#dynamic-table').DataTable().column(3).search(this.value).draw();
        } else {
            $('#dynamic-table').DataTable().column(12).search(this.value).draw();
        }
    });
     $('#to').on('change', function () {
        if (!this.value) {
            //$('#dynamic-table').DataTable().column(3).search(this.value).draw();
        } else {
            $('#dynamic-table').DataTable().column(12).search(this.value).draw();
        }
    });

}).call(this);

