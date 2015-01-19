<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>

<input type="hidden" id="HelpID" name="HelpID" value="<?php echo $this->data('Help', 'HelpID')?>">
<input type="hidden" id="HelpRevisionID" name="HelpRevisionID" value="<?php echo $this->data('HelpRevision', 'HelpRevisionID')?>">
<div class="form-group">
    <label for="Title">Title</label>
    <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" value="<?php echo $this->data('Help','Title')?>">
</div>

<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            <label for="Body">Body</label>
            <textarea id="Body" name="Body" cols="80" rows="20" class="form-control"><?php echo $this->data('HelpRevision','Body')?></textarea>
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
