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
    </tr>
    </thead>
    <tbody>
    <?php foreach($this->data['HelpList'] as $key=>$val) {?>
    <tr>
        <th scope="row"><?php echo $val['HelpID']?></th>
        <td><a href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Detail', $val['HelpID'])?>"><?php echo $val['Title']?></a> </td>
        <td><a href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Detail', $val['HelpID'])?>">View detail</a> </td>
        <td><a href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Edit', $val['HelpID'])?>">Edit</a> </td>
    </tr>
    <?php }?>
    </tbody>
</table>

<div class="text-center">
    <?php echo Smpe_Html_Pagination::full(Smpe_Mvc_Url::http('Support', 'Help', 'Browse'), $this->data['HelpCount'], $this->pagination['PageSize'], $_GET)?>
</div>