<?php include_once 'config.php'; ?>
<?php include_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <?php include 'header.php'; ?>
</head>
<body >

<header id="header_part">
    <div class="header_part" id="head">
        <div class="overlay">
            <div class="start_part">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <!-- Logo Start -->
                                <div class="site_logo">
                                    <a href="#" title=""><img height="80" src="images/logo.png" alt="" title=""/></a>
                                </div>
                                <!-- Logo End-->
                                <!-- Site naslov start-->
                                <div class="site_title">
                                    <h1>Prijava</h1>
                                </div>
                                <!-- Site naslov end-->

                                <!-- Forma za login -> start -->

                                <div class="text-center">

                                    <form method="post" style="color: floralwhite ">

                                        <h3><?php login_try(); ?></h3>

                                        <div class="form-group">
                                            <label>E-mail:
                                                <input type="email" name="email" class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label>Lozinka:
                                                <input type="text" name="lozinka" class="form-control">
                                            </label>
                                        </div>

                                        <div class="form-group">

                                            <input type="submit" name="submit" class="btn btn-primary" value="Prijava" >

                                        </div>

                                    </form>

                                </div>

                                <!-- Forma for login -> kraj -->
                                
                                <!-- Pozivamo funkciju za prijavu -->

                                <?php

                                if(isset($_POST['email']) && isset($_POST['lozinka']) &&
                                    !empty($_POST['email']) && !empty($_POST['lozinka']))
                                {
                                    log_in($_POST['email'], $_POST['lozinka']);
                                }

                                ?>
                                <!-- Pozivamo funkciju za prijavu -> kraj-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- izbornik -->
            <?php include 'menu.php'; ?>
            
        </div>
    </div>
</header>

<?php include 'scripts.php' ?>
</body>
</html>