$(document).ready(function(){

	$('#submit_pembiayaan').click(function(e) {
		 e.preventDefault();
		 alertify.confirm('Konfirmasi', 'Anda akan melakukan transaksi pembiayaan. Lanjutkan?  ', 
		 	function(){ 
		 		$.LoadingOverlay("show");
		 		$('#submit_pembiayaan').prop('disabled', true).addClass('btn btn-default');
			 	$( "#form_pembiayaan" ).submit(); 
			 }, 
			 function(){ 
			 	$('#modalPayment').modal('hide');
			 });
		 return false;
	});

	$('#submit_bayarcicilan').click(function(e) {
		 e.preventDefault();
		 alertify.confirm('Konfirmasi', 'Anda akan melakukan transaksi pembayaran cicilan. Lanjutkan?  ', 
		 	function(){ 
		 		$.LoadingOverlay("show");
		 		$('#submit_bayarcicilan').prop('disabled', true).addClass('btn btn-default');
			 	$( "#form_pembayaran" ).submit(); 
			 }, 
			 function(){ 
			 	$('#modalPayment').modal('hide');
			 });
		 return false;
	});

	$('#submit_topup').click(function(e) {
		 e.preventDefault();
		 var valid = $("#form_topup").validationEngine('validate');
		 if (valid == true) {
			$(this).hide();
			$('#img_loading').show();
			$( "#form_topup" ).submit();
			 return false;
		}
	});
	$('#submit_topup_oto').click(function(e) {
		 e.preventDefault();
		 var valid = $("#form_topup_oto").validationEngine('validate');
		 if (valid == true) {
			$(this).hide();
			$('#img_loading_oto').show();
			$( "#form_topup_oto" ).submit();
			 return false;
		}
	});

	$('#submit_redeem').click(function(e) {
		 e.preventDefault();
		 var valid = $("#form_redeem").validationEngine('validate');
		 if (valid == true) {
			$(this).hide();
			$('#img_loading').show();
			$( "#form_redeem" ).submit();
			 return false;
		}
	});
});