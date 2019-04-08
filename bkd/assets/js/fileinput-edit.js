var IMG_profil                             = $("#foto_file").attr("namafile");
var IMG_NIK                                = $("#ktp_file").attr("namafile");
var IMG_B                                  = $("#usaha_file").attr("namafile");
var IMG_B2                                  = $("#usaha_file2").attr("namafile");
var IMG_B3                                  = $("#usaha_file3").attr("namafile");
var IMG_B4                                  = $("#usaha_file4").attr("namafile");
var IMG_B5                                  = $("#usaha_file5").attr("namafile");
var IMG_surat_keterangan_bekerja_file      = $("#surat_keterangan_bekerja_file").attr("namafile");
var IMG_slip_gaji_file                     = $("#slip_gaji_file").attr("namafile");
var IMG_pegang_ktp_file                    = $("#pegang_ktp_file").attr("namafile");

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
$("#usaha_file2").fileinput({
  initialPreview: ["<img src="+IMG_B2+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});
$("#usaha_file3").fileinput({
  initialPreview: ["<img src="+IMG_B3+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});
$("#usaha_file4").fileinput({
  initialPreview: ["<img src="+IMG_B4+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});
$("#usaha_file5").fileinput({
  initialPreview: ["<img src="+IMG_B5+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});

$("#surat_keterangan_bekerja_file").fileinput({
  initialPreview: ["<img src="+IMG_surat_keterangan_bekerja_file+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});

$("#slip_gaji_file").fileinput({
  initialPreview: ["<img src="+IMG_slip_gaji_file+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});

$("#pegang_ktp_file").fileinput({
  initialPreview: ["<img src="+IMG_pegang_ktp_file+" class='file-preview-image' onerror=\"this.onerror=null;this.src=BASEURL+'assets/images/no-image.png';\" />"],
    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
    removeClass: "btn btn-danger",
    //removeLabel: "Remove",
    //removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 1000,
    showUpload: false
});

