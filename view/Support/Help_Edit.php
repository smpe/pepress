<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>
<link href="<?php echo Smpe_Mvc_Url::pub('/src/showdown/example/showdown-gui.css')?>" rel="stylesheet">
<div>
    <a class="btn btn-default" href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Browse')?>" role="button">Browse</a>
    <a class="btn btn-default" href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Detail', $this->data['Help']['HelpID'])?>" role="button">View</a>
</div>
<form id="form1" method="post" action="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'EditSubmit')?>">
    <input type="hidden" id="HelpID" name="HelpID" value="<?php echo $this->data['Help']['HelpID']?>">
    <input type="hidden" id="HelpRevisionID" name="HelpRevisionID" value="<?php echo $this->data['HelpRevision']['HelpRevisionID']?>">
    <div id="pageHeader">
        <h1><input type="text" id="Title" name="Title" value="<?php echo $this->data['Help']['Title']?>"><button type="submit" class="btn btn-primary">Save</button></h1>
        <h4></h4>
    </div>

    <div id="leftContainer">
        <div class="paneHeader">
            <span>Input</span>
        </div>
        <textarea id="inputPane" name="Body" cols="80" rows="20" class="pane"><?php echo $this->data['HelpRevision']['Body']?></textarea>
    </div>

    <div id="rightContainer">
        <div class="paneHeader">
            <select id="paneSetting">
                <option value="previewPane">Preview</option>
                <option value="outputPane">HTML Output</option>
                <option value="syntaxPane">Syntax Guide</option>
            </select>
        </div>
        <textarea id="outputPane" class="pane" cols="80" rows="20" readonly="readonly"></textarea>
        <div id="previewPane" class="pane"><noscript><h2>You'll need to enable Javascript to use this tool.</h2></noscript></div>
        <textarea id="syntaxPane" class="pane" cols="80" rows="20" readonly="readonly"></textarea>
    </div>

    <div id="footer">
        <span id="byline"></span>
		<span id="convertTextControls">
			<button id="convertTextButton" type="button" title="Convert text now">Convert text</button>
			<select id="convertTextSetting">
                <option value="delayed">in the background</option>
                <option value="continuous">every keystroke</option>
                <option value="manual">manually</option>
            </select>
		</span>
        <div id="processingTime" title="Last processing time">0 ms</div>
    </div>
</form>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/showdown/showdown.js')?>"></script>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/showdown/example/showdown-gui.js')?>"></script>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/jquery/jquery.form.js')?>"></script>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/jquery-validation/jquery.validate.js')?>"></script>
<script>
    $(document).ready(function(){
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
                            window.location = "<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Detail', $this->data['Help']['HelpID'])?>"
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                    }
                })
            }
        })
    })
</script>