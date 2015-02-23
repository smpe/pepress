<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>

<div class="container">
    <div class="text-right">
        <a class="btn btn-default" href="<?php echo Smpe_Url::http('Support', 'Help', 'Browse')?>" role="button">Browse</a>
        <a class="btn btn-default" href="<?php echo Smpe_Url::http('Support', 'Help', 'Edit', array($this->data['Help']['HelpID']))?>" role="button">Edit</a>
        <a class="btn btn-default" href="javascript:deleteHelp(<?php echo $this->data['Help']['HelpID']?>);" role="button">Delete</a>
    </div>
    <div class="hide" id="help_body"><?php echo $this->data['HelpRevision']['Body']?></div>
    <div id="help_body_html"></div>
</div>

<script src="<?php echo Smpe_Url::pub('/src/showdown/showdown.js')?>"></script>
<script>
    // build the converter
    var converter = new Showdown.Converter();

    // Do the conversion
    $("#help_body_html").html(converter.makeHtml($("#help_body").html()))

    //Delete help.
    function deleteHelp(id) {
        $.post("<?php echo Smpe_Url::http('Support', 'Help', 'DeleteSubmit')?>", {HelpID: id}, function(data){
            if(data.data > 0) {
                window.location = "<?php echo Smpe_Url::http('Support', 'Help', 'Browse')?>"
            } else {
                alert(data.msg)
            }
        }, "json")
    }
</script>
