<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>

<div class="container">
    <div>
        <a class="btn btn-default" href="<?php echo Smpe_Url::http('Support', 'Help', 'Browse')?>" role="button">Browse</a>
    </div>

    <form id="form1" method="post" action="<?php echo Smpe_Url::http('Support', 'Help', 'AddSubmit')?>">
        <div class="form-group">
            <label for="Title">Title</label>
            <input type="email" class="form-control" id="Title" name="Title" placeholder="Title">
        </div>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="Body">Body</label>
                    <textarea id="Body" name="Body" cols="80" rows="20" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="previewPanel">Preview</label>
                    <div id="previewPanel" class="form-control"></div>
                </div>
            </div>
        </div>

        <div id="processingTime" title="Last processing time">0 ms</div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>

<script src="<?php echo Smpe_Url::pub('/src/showdown/showdown.js')?>"></script>
<script src="<?php echo Smpe_Url::pub('/src/jquery/jquery.form.js')?>"></script>
<script src="<?php echo Smpe_Url::pub('/src/jquery-validation/jquery.validate.js')?>"></script>
<script>
    // build the converter
    var converter = new Showdown.converter();

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
                            window.location = "<?php echo Smpe_Url::http('Support', 'Help', 'Detail')?>/"+data.data
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                    }
                })
            }
        })

        convert()
    })
</script>