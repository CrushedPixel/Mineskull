// main.js
// By TheDestruc7i0n (http://thedestruc7i0n.ca)
$(document).ready(function () {
    var client = new ZeroClipboard($(".copyable"));
    // I did the file upload by making it so that
    // when you click on the fake button, it will
    // click on the real upload button
    $(".choosefile").click(function() {
        $(".uploadBtn").trigger("click");
    });
    $(".uploadBtn").change(function() {
        $(".uploadFile").val($(this).val());
    });
});