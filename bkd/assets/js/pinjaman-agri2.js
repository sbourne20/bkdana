// $(document).ready(function () {
//   function disableButton() {
//       $("#btnSubmit").prop('disabled', true);
//   }
//   function enableButton() {
//       $("#btnSubmit").prop('disabled', false);
//   }

//   $('#btnSubmit').click(function(e) {
//          e.preventDefault();

//          var valid = $("#form-pinj-agri").validationEngine('validate');
//          if (valid == true) {
//              alertify.confirm('Konfirmasi', 'Anda akan mengajukan pinjaman Kilat. Lanjutkan?  ', 
//                 function(){ 
//                     set_ajax();
//                  }, 
//                  function(){ 
//                     $('#modalPayment').modal('hide');
//                  });
//              return false;
//          }
//     });
// });

// function set_ajax(){

//       $.ajax
//         ({
//             type: "POST",
//             url: BASEURL + "submit-pinjaman-agri",
//             data: $("#form-pinj-agri").serialize(),
//             beforeSend: function() {
//                 $('.next').prop('disabled', true);
//             },
//             success: function(html_data)
//             {
//               /*alert(html_data);$('.next').prop('disabled', false);*/
//               objdata = JSON.parse(html_data);
//               if (objdata.error == '1')
//               {
//                 $('.next').prop('disabled', false);
//                 alertify.alert('Error Message!',objdata.message);
//                 return false;
//               }else{
//                   window.location.replace(BASEURL +'dashboard');
//                   return false;
//               }
//             },
//             error: function (request, status, error) {
//                 alert(request.responseText);
//             }
//         });
    
// }

// $('#jumlah_pinjam').change(function() {
//     var hargaid   = $('option:selected', this).attr('data-hargaid');
//     var send_data = {'id_harga' : hargaid };
//     $.ajax
//         ({
//             type: "POST",
//             url: BASEURL + "ajax/tenor_pinjaman_agri",
//             data: send_data,
//             beforeSend: function() {
//             },
//             success: function(html_data)
//             {
//               $('#product').html(html_data);
//             },
//             error: function (request, status, error) {
//                 /*alert(request.responseText);*/
//             }
//         });
// });

$(document).ready(function () {
  function disableButton() {
      $("#btnSubmit").prop('disabled', true);
  }
  function enableButton() {
      $("#btnSubmit").prop('disabled', false);
  }

  $('#btnSubmit').click(function(e) {
         e.preventDefault();
         var isfoto = validate_foto();

         var valid = $("#form-pinj-agri").validationEngine('validate');
         if (valid == true && isfoto == true) {
             alertify.confirm('Konfirmasi', 'Anda akan mengajukan pinjaman Agri. Lanjutkan?  ', 
                function(){ 
                    set_ajax();
                 }, 
                 function(){ 
                    $('#modalPayment').modal('hide');
                 });
             return false;
         }
    });
});

function validate_foto(){
           var files = $("#cf_file").val();
           var files1 = $("#progress_report_file").val();
           var files2 = $("#hasil_panen_file1").val(); 
           var files3 = $("#hasil_panen_file2").val(); 
           var files4 = $("#hasil_panen_file3").val(); 

           if(files==''){ 
             alert("Upload Contract File Anda!"); 
             return false;
           }else{
              return true;
           }

           if(files1==''){ 
             alert("Upload Progress Report Anda!"); 
             return false;
           }else{
              return true;
           }
         
           if(files2==''){ 
             alert("Upload Foto Hasil Panen Anda!"); 
             return false;
           }else{
              return true;
           }
        
           if(files3==''){ 
             alert("Upload Foto Hasil Panen 2 Anda!"); 
             return false;
           }else{
              return true;
           }
        
           if(files4==''){ 
             alert("Upload Foto Hasil Panen 3 Anda!"); 
             return false;
           }else{
              return true;
           }
        }

function set_ajax(){

  var a = new FormData(); // using additional FormData object
    var b = [];             // using an array
    for(var i = 0; i < document.forms.length; i++){
        var form = document.forms[i];
        var data = new FormData(form);
        var formValues = data.entries()
        while (!(ent = formValues.next()).done) {

            // Note the change here 
            //console.log(`${ent.value[0]}[]`, ent.value[1])
        }
        // Ajax Here
    }

    var send_data = new FormData(form); //create a FormData object
    var firstform = jQuery(document.forms['form_name1']).serializeArray();
    for (var i=0; i<firstform.length; i++)
        {
            send_data.append(firstform[i].name, firstform[i].value);
        }

      $.ajax
        ({
            type: "POST",
            url: BASEURL + "submit-pinjaman-agri",
            data: send_data,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.next').prop('disabled', true);
            },
            success: function(html_data)
            {
              /*alert(html_data);$('.next').prop('disabled', false);return false;*/
              objdata = JSON.parse(html_data);
              if (objdata.error == '1')
              {
                $('.next').prop('disabled', false);
                alertify.alert('Error Message!',objdata.message);
                return false;
              }else{
                 window.location.replace(BASEURL + 'dashboard');
                 return false;
              }
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    
}