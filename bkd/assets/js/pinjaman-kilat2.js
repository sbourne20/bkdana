$(document).ready(function () {
  function disableButton() {
      $("#btnSubmit").prop('disabled', true);
  }
  function enableButton() {
      $("#btnSubmit").prop('disabled', false);
  }

  $('#btnSubmit').click(function(e) {
         e.preventDefault();

         var valid = $("#form-pinj-kilat").validationEngine('validate');
         if (valid == true) {
             alertify.confirm('Konfirmasi', 'Anda akan mengajukan pinjaman Kilat. Lanjutkan?  ', 
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

function set_ajax(){

      $.ajax
        ({
            type: "POST",
            url: BASEURL + "submit-pinjaman-kilat",
            data: $("#form-pinj-kilat").serialize(),
            beforeSend: function() {
                $('.next').prop('disabled', true);
            },
            success: function(html_data)
            {
              /*alert(html_data);$('.next').prop('disabled', false);*/
              objdata = JSON.parse(html_data);
              if (objdata.error == '1')
              {
                $('.next').prop('disabled', false);
                alertify.alert('Error Message!',objdata.message);
                return false;
              }else{
                  window.location.replace(BASEURL +'dashboard');
                  return false;
              }
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    
}

$('#jumlah_pinjam').change(function() {
    var hargaid   = $('option:selected', this).attr('data-hargaid');
    var send_data = {'id_harga' : hargaid };
    $.ajax
        ({
            type: "POST",
            url: BASEURL + "ajax/tenor_pinjaman_kilat",
            data: send_data,
            beforeSend: function() {
            },
            success: function(html_data)
            {
              $('#product').html(html_data);
            },
            error: function (request, status, error) {
                /*alert(request.responseText);*/
            }
        });
});