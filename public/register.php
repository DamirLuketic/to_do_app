<?php include_once 'config.php'; ?>
<?php include 'functions.php'; ?>
<!-- osnovna poruka za korisnika -> mijenja se po potrebi -->
<?php $_SESSION['msg'] = 'Nakon uspješne registracije, potrebno je potvrditi e-mail'; ?>
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
                                    <h1>Registracija</h1>
                                </div>
                                <!-- Site naslov end-->

                                <!-- zovemo funkciju za registraciju -->

                                <?php

                                if(isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['email']) && isset($_POST['lozinka']) && isset( $_POST['ponovljena_lozinka']) &&
                                    !empty($_POST['ime']) && !empty($_POST['prezime']) && !empty($_POST['email']) && !empty($_POST['lozinka']) && !empty( $_POST['ponovljena_lozinka'])
                                )
                                {
                                    registracija($_POST['ime'], $_POST['prezime'], $_POST['email'], $_POST['lozinka'], $_POST['ponovljena_lozinka']);
                                }
                                ?>

                                <!-- zovemo funkciju za registraciju -> kraj -->

                                <!-- Forma za registraciju -->
                                
                                <div class="text-center">
                                    <form method="post" style="color: floralwhite ">

                                        <h3><?php echo $_SESSION['msg']; ?></h3>

                                            <div class="form-group">
                                                <label>Ime:
                                                    <input type="text" name="ime" class="form-control">
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>Prezime:
                                                    <input type="text" name="prezime" class="form-control">
                                                </label>
                                            </div>

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
                                                <label>Ponovite lozinku:
                                                    <input type="text" name="ponovljena_lozinka" class="form-control">
                                                </label>
                                            </div>

                                            <div class="form-group">

                                                    <input type="submit" name="submit" class="btn btn-primary" value="Registracija" >

                                            </div>
                                    </form>
                                </div>

                                <!-- forma za registraciju / kraj -->

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