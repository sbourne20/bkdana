var IMG_profil = $("#foto_file").attr("namafile");
var IMG_NIK    = $("#ktp_file").attr("namafile");
var IMG_B      = $("#usaha_file").attr("namafile");

$("#foto_file").fileinput({
  initialPreview: ["<img src="+IMG_profil+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});

$("#ktp_file").fileinput({
  initialPreview: ["<img src="+IMG_NIK+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});

$("#usaha_file").fileinput({
  initialPreview: ["<img src="+IMG_B+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});