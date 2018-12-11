
  (function() {    

    var dataTable = $('#dynamic-table').dataTable( {
        "bProcessing" : true,
        "bServerSide" : true,
        "bStateSave"  : true,
        "sAjaxSource" : baseURL + "product/json",
        "columns": [
          { "data": "Product_id" },
          { "data": "product_title" },
          { "data": "Type_of_business", "sClass": "hidden-xs" },
          { "data" : function ( data, type, full, meta ) {
              return data.Interest_rate + ' %';
            }, "sClass": "hidden-xs"
          },
          { "data" : function ( data, type, full, meta ) {
            if (data.type_of_business_id == '1'){
               if (data.type_of_interest_rate == 1)
                {
                   // $('#tipe_loan_term').text('days'); 
                    return data.Loan_term + ' Hari';       
                }
                if (data.type_of_interest_rate == 3)
                {
                   // $('#tipe_loan_term').text('weeks');
                    return data.Loan_term + ' Minggu';        
                }
                if(data.type_of_interest_rate == 2){
                   // $('#tipe_loan_term').text('months');
                    return data.Loan_term + ' Bulan';                 
                }        
                //return data.Loan_term + ' Hari';
            }else{
               if (data.type_of_interest_rate == 1)
                {
                   // $('#tipe_loan_term').text('days'); 
                    return data.Loan_term + ' Hari';       
                }
                if (data.type_of_interest_rate == 3)
                {
                   // $('#tipe_loan_term').text('weeks');
                    return data.Loan_term + ' Minggu';        
                }
                if(data.type_of_interest_rate == 2){
                   // $('#tipe_loan_term').text('months');
                    return data.Loan_term + ' Bulan';                 
                }
              //return data.Loan_term + ' Bulan';              
            }
            }, "sClass": "hidden-xs"
          },
          { "data": "Product_sector", "sClass": "hidden-xs" },
          { "data": function ( data, type, full, meta ) {
              if (data.product_status=='1') {
                var ret='<span class="label label-primary">Active</span>'; 
              }else{
                var ret='<span class="label label-default">Not Active</span>';                 
              }
              return ret;
            }, "sClass": "hidden-xs"
          },
          { "data" : function ( data, type, full, meta ) {
              var btn_group='<div class="btn-group">'+
                      '<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button">Actions <span class="caret"></span></button>'+
                      '<ul role="menu" class="dropdown-menu action-menu">'+
                          '<li><a href="#myModal" onclick="view('+data.Product_id+')" data-toggle="modal"><i class="fa fa-eye"></i> View </a></li>'+
                          '<li><a href="javascript:;" onclick="edit_('+data.Product_id+')"><i class="fa fa-pencil"></i> Edit</a></li>'+                      
                          '<li class="divider"></li>'+
                          '<li><a href="javascript:;" onclick="delete_('+data.Product_id+')"><i class="fa fa-trash-o"></i> Delete</a></li>'+
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
  var c= confirm("Are you sure will delete this product?");
  if(c==true){
    window.location.href = base_uri  + '/delete/' + id;
  }
  return false;
}

function edit_(id) {
    window.location.href = base_uri  + '/edit/' + id;
}