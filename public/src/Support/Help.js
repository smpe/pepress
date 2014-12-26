// build the converter
var converter = new Showdown.Converter();

function convert() {
    $("#Body").css("height", "auto")
    $("#previewPanel").css("height", "auto")

    var startTime = new Date().getTime()

    // Do the conversion
    $("#previewPanel").html(converter.makeHtml($("#Body").val()))

    // display processing time
    var endTime = new Date().getTime();
    var processingTime = endTime - startTime;
    $("#processingTime").html(processingTime+" ms")

    $("#Body").css("height", $("#Body").attr("scrollHeight")+"px")
    $("#previewPanel").css("height", $("#previewPanel").attr("scrollHeight")+"px")
    var bh = $("#Body").height()
    var ph = $("#previewPanel").height()
    if(bh > ph) {
        $("#previewPanel").height(bh)
    } else {
        $("#Body").height(ph)
    }
}

$(document).ready(function(){
    $("#Body").keyup(function(event){
        convert()
    })

    $("#form1").validate({
        submitHandler: function(form){
            $(form).ajaxSubmit({
                dataType: "json",
                success: function(data, textStatus, jqXHR){
                    if(data == null || typeof data.data == "undefined" || typeof data.msg == "undefined"){
                        return
                    }
                    if(data.data <= 0) {
                        alert(data.msg)
                    } else {
                        window.location = data.msg
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                }
            })
        }
    })

    convert()
})
