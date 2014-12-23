<div>
    <a class="btn btn-default" href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Browse')?>" role="button">Browse</a>
    <a class="btn btn-default" href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Edit', $this->data['Help']['HelpID'])?>" role="button">Edit</a>
</div>
<div class="hide" id="help_body"><?php echo $this->data['HelpRevision']['Body']?></div>
<div id="help_body_html"></div>

<script src="<?php echo Smpe_Mvc_Url::pub('/src/showdown/showdown.js')?>"></script>
<script>
    // build the converter
    converter = new Showdown.converter()
    // Do the conversion
    $("#help_body_html").html(converter.makeHtml($("#help_body").html()))
</script>
