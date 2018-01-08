$(document).ready(function () {
    $(document).on("change", "#concrete-file", function () {
        $("#concrete-file-form").submit();
    });

    $("#concrete-file-form").ajaxForm({
        success: function (responseText, statusText, xhr, $form) {
            $("#uploaded-file").val(responseText.file);
            $("#uploaded-url").attr("src", responseText.url);
        }
    });
});