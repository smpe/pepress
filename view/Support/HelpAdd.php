<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>

<div class="container">
    <div class="text-right">
        <a class="btn btn-default" href="<?php echo Smpe_Url::http('Support', 'Help', 'Browse')?>" role="button">Browse</a>
    </div>

    <form id="form1" method="post" action="<?php echo Smpe_Url::http('Support', 'Help', 'AddSubmit')?>">
        <?php $this->block('Support', 'HelpForm')?>
    </form>
</div>

<script src="<?php echo Smpe_Url::pub('/src/showdown/showdown.js')?>"></script>
<script src="<?php echo Smpe_Url::pub('/src/jquery/jquery.form.js')?>"></script>
<script src="<?php echo Smpe_Url::pub('/src/jquery-validation/jquery.validate.js')?>"></script>
<script src="<?php echo Smpe_Url::pub('/src/Support/Help.js')?>"></script>
