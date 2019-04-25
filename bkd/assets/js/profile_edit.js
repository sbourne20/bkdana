$( document ).ready(function() {
    console.log( "ready!" );

    $('#provinsi').change(function() {
    var provinsi   = $(this).val();
    // var member    = $('data-member');
    console.log(provinsi);
    var base_uri  = window.location.href;

    var send_data = {'Option_key' : provinsi };
    $.ajax({
            type: "POST",
            url: BASEURL + "Member/kota_provinsi",
            data: send_data,
            beforeSend: function() {
            },
            success: function(html_data)
            {
            var kota = $('#kota'); 
                kota.html(html_data);
                if(kota.data("value") != ""){
                    $('#kota option[value='+kota.data("value")+']').prop('selected','selected');
                }
            },
            error: function (request, status, error) {
                /*alert(request.responseText);*/
            }
        });
});

if($('#provinsi').val()!=""){
    $('#provinsi').trigger("change");
}


  $('#provinsidomisili').change(function() {
    var provinsidomisili   = $(this).val();
    // var member    = $('data-member');
    console.log(provinsidomisili);
    var base_uri  = window.location.href;

    var send_data = {'Option_key' : provinsidomisili };
    $.ajax({
            type: "POST",
            url: BASEURL + "Member/kota_provinsi",
            data: send_data,
            beforeSend: function() {
            },
            success: function(html_data)
            {
            var kotadomisili = $('#kotadomisili'); 
                kotadomisili.html(html_data);
                if(kotadomisili.data("value") != ""){
                    $('#kotadomisili option[value='+kotadomisili.data("value")+']').prop('selected','selected');
                }
            },
            error: function (request, status, error) {
                /*alert(request.responseText);*/
            }
        });
});

if($('#provinsidomisili').val()!=""){
    $('#provinsidomisili').trigger("change");
}

});

