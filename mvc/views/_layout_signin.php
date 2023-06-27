<?php echo doctype("html5"); ?>
<html class="white-bg-login" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Sign in</title>
    <link rel="SHORTCUT ICON" href="<?=base_url("uploads/images/$siteinfos->photo")?>" />
    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet"  type="text/css">
    <!-- font Awesome -->
    <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet"  type="text/css">
    <!-- Style -->
    <link href="<?php echo base_url($backendThemePath.'/style.css'); ?>" rel="stylesheet"  type="text/css">
    <!-- iNilabs css -->
    <link href="<?php echo base_url($backendThemePath.'/inilabs.css'); ?>" rel="stylesheet"  type="text/css">
    <link href="<?php echo base_url('assets/inilabs/responsive.css'); ?>" rel="stylesheet"  type="text/css">
</head>

<body class="white-bg-login">

    <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
        <?php
            if(count($siteinfos->photo)) {
                echo "<center><img width='50' height='50' src=".base_url('uploads/images/'.$siteinfos->photo)." /></center>";
            }
        ?>
        <center><h4><?php echo namesorting($siteinfos->sname, 25); ?></h4></center>
    </div>

    <?php $this->load->view($subview); ?>

    <?php if(config_item('demo')) { ?>

        <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
    	<nav class="navbar navbar-default">
    	  <div class="container-fluid">
    	    <div class="navbar-header">
    	      <a class="navbar-brand" href="#">
    	       &nbsp; &nbsp; &nbsp; For Quick Demo Login Click Below...
    	      </a>
    	    </div>
    	  </div>
    	</nav>
    	</div>
    	<div class="col-md-6 col-md-offset-3 marg" >
    	    <center>
    	        <div class="btn-group" role="group" aria-label="...">
    	            <button class="btn btn-sm btn-primary" id="admin">Admin</button>
    	            <button class="btn btn-sm btn-info" id="teacher">Teacher</button>
    	            <button class="btn btn-sm btn-warning" id="student">Student</button>
    	            <button class="btn btn-sm btn-success" id="parent">Parent</button>
    	            <button class="btn btn-sm btn-danger" id="accountant">Accountant</button>
    	            <button class="btn btn-sm btn-default" id="librarian">Librarian</button>
    		    <button class="btn btn-sm btn-primary" id="recep">Receptionist</button>
    	        </div>
    	    </center>
    	</div>

    <?php } ?>

    <script type="text/javascript" src="<?php echo base_url('assets/inilabs/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/bootstrap.min.js'); ?>"></script>

    <?php if(config_item('demo')) { ?>
        <script type="text/javascript">
            $('#admin').click(function () {
                $("input[name=username]").val('admin');
                $("input[name=password]").val('123456');
            });
            $('#teacher').click(function () {
                $("input[name=username]").val('teacher1');
                $("input[name=password]").val('123456');
            });
            $('#student').click(function () {
                $("input[name=username]").val('student1');
                $("input[name=password]").val('123456');
            });
            $('#parent').click(function () {
                $("input[name=username]").val('parent1');
                $("input[name=password]").val('123456');
            });
            $('#accountant').click(function () {
                $("input[name=username]").val('accountant');
                $("input[name=password]").val('123456');
            });
            $('#librarian').click(function () {
                $("input[name=username]").val('librarian');
                $("input[name=password]").val('123456');
            });
            $('#recep').click(function () {
                $("input[name=username]").val('receptionist');
                $("input[name=password]").val('123456');
            });


          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-61634883-2', 'auto');
          ga('send', 'pageview');
        </script>
    <?php } ?>
</body>
</html>
