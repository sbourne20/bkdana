$(document).ready(function() {
    $("#userform").select2();
    $("#privilege").select2();
    $("#select2").select2();
    $("#category").select2({
        placeholder: " ------- Pilih --------"
    });
    $("#editsubcategory").select2({
        placeholder: " ------- Pilih --------"
    });
    $("#brand").select2({
        placeholder: " ------- Pilih --------"
    });
    $("#size").select2({
        placeholder: " ------- Pilih --------"
    });
    $(".lookbooks").select2({
        placeholder: " ------- Choose Collection --------"
    });
});