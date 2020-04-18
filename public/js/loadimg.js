function readFile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var htmlPreview =
                '<img width="100" src="' +
                e.target.result +
                '" />' +
                "<p>" +
                input.files[0].name +
                "</p>";
            console.log(e.target.result);
            var wrapperZone = $(input).parent();
            var previewZone = $(input)
                .parent()
                .parent()
                .find(".preview-zone");
            var boxZone = $(input)
                .parent()
                .parent()
                .find(".preview-zone")
                .find(".box")
                .find(".box-body");

            // wrapperZone.removeClass("dragover");
            //previewZone.removeClass("hidden");
            //boxZone.empty();
            console.log(e.target.result);
            boxZone.append(htmlPreview);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function reset(e) {
    e.wrap("<form>")
        .closest("form")
        .get(0)
        .reset();
    e.unwrap();
}

$("#dropzoneimg").change(function () {
    readFile(this);
});

$(".dropzone-wrapper").on("dragover", function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass("dragover");
});

$(".dropzone-wrapper").on("dragleave", function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass("dragover");
});

$(".remove-preview").on("click", function () {
    var boxZone = $(this)
        .parents(".preview-zone")
        .find(".box-body");
    var previewZone = $(this).parents(".preview-zone");
    var dropzone = $(this)
        .parents(".form-group")
        .find(".dropzone");
    boxZone.empty();
    previewZone.addClass("hidden");
    reset(dropzone);
});

$(document).ready(function () {
    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });

    $('.btn-file :file').on('fileselect', function (event, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = label;

        if (input.length) {
            input.val(log);
        } else {
            // if (log) alert(log);
        }

    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function () {
        readURL(this);
    });
});
