<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Smpe - </title>
    <link href="<?php echo Smpe_Mvc_Url::pub('/src/bootstrap/css/bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo Smpe_Mvc_Url::theme('/style.css')?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo Smpe_Mvc_Url::pub('/lib/ie/html5shiv.min.js')?>"></script>
    <script src="<?php echo Smpe_Mvc_Url::pub('/lib/ie/respond.min.js')?>"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php $this->view();?>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/jquery/jquery.js')?>"></script>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/bootstrap/js/bootstrap.js')?>"></script>
<script src="<?php echo Smpe_Mvc_Url::pub('/src/ie/ie10-viewport-bug-workaround.js')?>"></script>
</body>
</html>
