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
    }

    var send_data = new FormData(form); //create a FormData object
    var firstform = jQuery(document.forms['form_name1']).serializeArray();
    for (var i=0; i<firstform.length; i++)
        {
            send_data.append(firstform[i].name, firstform[i].value);
        }
     
    var secondform = jQuery(document.forms['form_name2']).serializeArray();
    for (var i=0; i<secondform.length; i++)
        {
        send_data.append(secondform[i].name, secondform[i].value);
        }
    var secondform = jQuery(document.forms['form_name3']).serializeArray();
    for (var i=0; i<secondform.length; i++)
        {
        send_data.append(secondform[i].name, secondform[i].value);
        }

    var agreement = $("#iAgree").prop('checked'); /*return true or false*/
    if (agreement==false) {
      alertify.alert('Error Message!', 'Anda harus mencentang checkbox "<em>Saya telah membaca dan saya setuju dengan Kebijakan Privasi & Syarat Ketentuan</em>"');
      return false;
    }else{
      $.ajax
        ({
          type: "POST",
          url: BASEURL + "submit-formulir-pendana",
          data: send_data,
          processData: false,
          contentType: false,
          success: function(html_data)
          {
            objdata = JSON.parse(html_data);
            if (objdata.error == '1')
            {
                alertify.alert('Error Message!',objdata.message);
                return false;
            }else{
                window.location.replace(BASEURL +'dashboard');
                return false;
            }
            /*alert(html_data);*/
          },
          error: function (request, status, error) {
              alert(request.responseText);
          }
        });
    }
}

var minLength = 6;
var maxLength = 20;
$(document).ready(function(){
    $('#password').on('keydown keyup change', function(){
        var char = $(this).val();
        var charLength = $(this).val().length;
        if(charLength < minLength){
            $('#passwordmsg').addClass('alert-danger').text('Password minimum '+minLength+' huruf.');
        }else if(charLength > maxLength){
            $('#passwordmsg').addClass('alert-danger').text('Password maximum '+maxLength+' huruf.');
            $(this).val(char.substring(0, maxLength));
        }else{
            $('#passwordmsg').removeClass('alert-danger').text('Length is valid');
        }
    });

    var $cf = $('#telp');
    $cf.blur(function(e){
      phone = $(this).val();
        if (!validatePhone('telp')) {
          $('#telperror').text('Invalid Telepon Format!');
          $('#telperror').show();
        }else if (phone.length < 11) {
          $('#telperror').text('Telp Minimum 10 digit!');
          $('#telperror').show();
        }else{
          $('#telperror').text('');
          $('#telperror').hide();
        }        
    });
});

function validatePhone(txtPhone) {
  var a = document.getElementById(txtPhone).value;
  var filter = /^[0-9-+]+$/;
  if (filter.test(a)) {
      return true;
  }
  else {
      return false;
  }
}