$(document).ready(function(){
	datepickerInit();
});

function datepickerInit(){
    if ($.fn.datepicker) {
    		$('.datepicker-dob').datepicker({
                format: 'dd-mm-yyyy',
                orientation: "bottom",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                startView:'decade',
                endDate : "-"+$('.datepicker-dob').attr("minimum-age")+"y",
            });
    }
}