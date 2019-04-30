function set_ajax() {

	var a = new FormData(); // using additional FormData object
	var b = []; // using an array
	for (var i = 0; i < document.forms.length; i++) {
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
	for (var i = 0; i < firstform.length; i++) {
		send_data.append(firstform[i].name, firstform[i].value);
	}

	var agreement = $("#iAgree").prop('checked'); /*return true or false*/
	if (agreement == false) {
		alertify.alert('Error Message!', 'Anda harus mencentang checkbox "<em>Saya telah membaca dan saya setuju dengan Kebijakan Privasi & Syarat Ketentuan</em>"');
		return false;
	} else {
		$.ajax({
			type: "POST",
			url: BASEURL + "ajax/register_check",
			data: send_data,
			processData: false,
			contentType: false,
			beforeSend: function () {},
			success: function (html_data) {
				/*alert(html_data);*/
				objdata = JSON.parse(html_data);
				if (objdata.error == '1') {
					alertify.alert('Error Message!', objdata.message);
					return false;
				} else {
					show_modal();
				}
			}
		});
	}
}

function show_modal() {
	var fullname = $('#fullname').val();
	var email = $('#email').val();
	var telp = $('#telp').val();
	var password = $('#password').val();
	var confirm_password = $('#confirm_password').val();
	var nomor_rekening = $('#nomor_rekening').val();
	var nama_bank = $('#nama_bank option:selected').val();
	var jumlah_pinjam = $('#jumlah_pinjam').val();
	var product = $('#product option:selected').text();
	$('#m_fullname').text(fullname);
	$('#m_email').text(email);
	$('#m_telp').text(telp);
	$('#m_password').text(password);
	$('#m_confirm_password').text(confirm_password);
	$('#m_nomor_rekening').text(nomor_rekening);
	$('#m_nama_bank').text(nama_bank);
	$('#m_jumlah_pinjam').text(jumlah_pinjam);
	$('#m_product').text(product);

	$('#modal_confirm').modal('show');
}

$('#btn-back').click(function (e) {
	e.preventDefault();
	$('#modal_confirm').modal('hide');
});
$('#btn-submit').click(function (e) {
	e.preventDefault();
	$('#modal_confirm').modal('hide');
	exec_submit();
});

function exec_submit() {
	var a = new FormData(); // using additional FormData object
	var b = []; // using an array
	for (var i = 0; i < document.forms.length; i++) {
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
	for (var i = 0; i < firstform.length; i++) {
		send_data.append(firstform[i].name, firstform[i].value);
	}

	$.ajax({
		type: "POST",
		url: BASEURL + "submit-register-pinjaman-agri",
		data: send_data,
		processData: false,
		contentType: false,
		beforeSend: function () {
			countDown();
			$('#modal_loading').modal('show');
			$('.next').prop('disabled', true);
			$('.previous').prop('disabled', true);
		},
		success: function (html_data) {
			objdata = JSON.parse(html_data);
			if (objdata.error == '1') {
				$('#modal_loading').modal('hide');
				$('.next').prop('disabled', false);
				$('.previous').prop('disabled', false);
				alertify.alert('Error Message!', objdata.message);
				return false;
			} else {
				//prompt('link ', objdata.activation_url);
				if (objdata.activation_url != null) {
					window.location.replace(objdata.activation_url);
				} else {
					window.location.replace(BASEURL + 'message/registrasi_success');
				}
			}
			//alert(html_data);                  
		},
		error: function (request, status, error) {
			alert(request.responseText);
		}
	});
}

var sec = 60;
var myTimer = document.getElementById('myTimer');
var myBtn = document.getElementById('myBtn');

function countDown() {
	if (sec < 10) {
		myTimer.innerHTML = "0" + sec;
	} else {
		myTimer.innerHTML = sec;
	}
	if (sec <= 0) {
		$("#myBtn").removeAttr("disabled");
		$("#myBtn").removeClass().addClass("btnEnable btn btn-primary");
		$("#myTimer").fadeTo(2500, 0);
		return;
	}
	sec -= 1;
	window.setTimeout(countDown, 1000);
}

function resend_email() {
	var emailu = encodeURIComponent($('#email').val());
	alertify.confirm('Konfirmasi', 'Kirim ulang Email Aktivasi?', function () {
		window.location.replace(BASEURL + 'resend-email-aktivasi?email=' + emailu);
	}, function () {});
}

history.pushState(null, null, location.href);
window.onpopstate = function () {
	history.go(1);
};
