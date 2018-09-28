$(document).ready(function() {
    jQuery("#formID").validationEngine();

    $('input#title').change(function(){
		$('#title').friendurl({id : 'slug'});
	});
	$('input#title').keyup(function(){
		$('#title').friendurl({id : 'slug'});
	});
});

function formatMoney(num, c, d, t){
var n = num, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };

var garing_date_format = function(input){
    var input = input.split(".");
    var d = new Date(Date.parse(input[0].replace(/-/g, "/")));
    var month_numeric = d.getMonth() + 1;
    if (month_numeric<10)
    {
        month_numeric = '0'+month_numeric;
    }
    var date = d.getDate() + "/" + month_numeric + "/" + d.getFullYear();
    /*var time = d.toLocaleTimeString().toLowerCase().replace(/([\d]+:[\d]+):[\d]+(\s\w+)/g, "$1$2");*/
    var time = (pad(d.getHours()) + ":" + pad(d.getMinutes()));
    /*return (date + ", " + time + " WIB");  */
    return (date);
};
var my_date_format = function(input){
    var input = input.split(".");
    var d = new Date(Date.parse(input[0].replace(/-/g, "/")));
    var month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var date = d.getDate() + " " + month[d.getMonth()] + " " + d.getFullYear();
    /*var time = d.toLocaleTimeString().toLowerCase().replace(/([\d]+:[\d]+):[\d]+(\s\w+)/g, "$1$2");*/
	var time = (pad(d.getHours()) + ":" + pad(d.getMinutes()));
    /*return (date + ", " + time + " WIB");  */
    return (date);
};

var short_date_format = function(input){
    var input = input.split(".");
    var d = new Date(Date.parse(input[0].replace(/-/g, "/")));
    var month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    /* getMonth() menghasilkan january => 0, february => 1 */
    var month_numeric = d.getMonth() + 1;

    if (month_numeric<10)
    {
        month_numeric = '0'+month_numeric;
    }

    var date = d.getDate() + "-" + month_numeric + "-" + d.getFullYear();
    /*var date = d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear();*/
    /*var time = d.toLocaleTimeString().toLowerCase().replace(/([\d]+:[\d]+):[\d]+(\s\w+)/g, "$1$2");*/
    var time = (pad(d.getHours()) + ":" + pad(d.getMinutes()));
    return (date + ", " + time + " WIB");  
};
function pad(value) {
    if(value < 10) {
        return '0' + value;
    } else {
        return value;
    }
}
/* Uppercase first letter */
String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}