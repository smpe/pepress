<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>
<div>
    <a class="btn btn-default" href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Add')?>" role="button">Add</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Topic</th>
        <th>View</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($this->data['HelpList'] as $key=>$val) {?>
    <tr>
        <th scope="row"><?php echo $val['HelpID']?></th>
        <td><?php echo $val['Title']?> </td>
        <td><a href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Detail', $val['HelpID'])?>">Detail</a> </td>
        <td><a href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Edit', $val['HelpID'])?>">Edit</a> </td>
        <td><a href="javascript:deleteHelp(<?php echo $val['HelpID']?>);">Delete</a> </td>
    </tr>
    <?php }?>
    </tbody>
</table>

<div class="text-center">
    <?php echo Smpe_Html_Pagination::full(Smpe_Mvc_Url::http('Support', 'Help', 'Browse'), $this->data['HelpCount'], $this->pagination['PageSize'], $_GET)?>
</div>

<script>
    function deleteHelp(id) {
        $.post("<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'DeleteSubmit')?>", {HelpID: id}, function(data){
            if(data.data > 0) {
                window.location.reload()
            } else {
                alert(data.msg)
            }
        }, "json")
    }
</script>