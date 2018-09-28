  /**
   * Signs out the user when the sign-out button is clicked.
   */
  function onSignOutClick() {
    window.location.replace(BASEURL+'logoff');
  }

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

$(document).ready(function () {
  function disableButton() {
      $("#btnSubmit").prop('disabled', true);
  }
  function enableButton() {
      $("#btnSubmit").prop('disabled', false);
  }

  if(jQuery("#form-resetp").length != 0){
  jQuery("#form-resetp").validationEngine('attach', {
    onValidationComplete: function(form, status){
      /*alert("The form status is: " +status+", it will never submit");*/
      if (status === true)
      {
        setTimeout(function () { disableButton(); }, 0);
        return true;  /* this is submit the form */
      }else{
        enableButton();
        return false;
      }
    }  
  });
  }
  
});