<?php include_once 'config.php'; ?>
<?php include_once 'functions.php'; ?>

<!-- "Paginacija" -> prvotni uvjeti -->

<?php

if(!isset($_GET["page"])){
    $page=1;
}else{
    $page = $_GET["page"];
}
if($page==0){
    $page=1;
}

// broj po stranici
$npp = 6;

// vrjednosti za grupiranje pretraživanja
if(isset($_POST["group"]))
{
    $group = $_POST["group"];
}else
{
    $group = 3;
};

if(isset($_POST["value"]))
{
    $value = $_POST["value"];
}else
{
    $value = 'asc';
};


?>
<!-- "Paginacija" -> kraj prvog dijela -->

<!-- Header Start -->
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
                                <!-- Naziv stranice start-->
                                <div class="site_title">
                                    <?php if(isset($_COOKIE['user_id'])): ?>
                                    <h1>Liste zadataka</h1>
                                    <?php else: ?>
                                    <h1>Za daljnji rad potrebna je prijava\registracija</h1>
                                    <?php endif; ?>
                                </div>
                                <!-- Naziv stranice end-->

                                <!-- start tablice za pregled lista -->

                                <!-- slaganje po datim kriterijima -->

                                <form method="post">
                                    <input type="hidden" name="group" value="3">
                                    <input type="submit" class="btn btn-info" value="Naziv">
                                </form>

                                <form method="post">
                                    <input type="hidden" name="group" value="4">
                                    <input type="submit" class="btn btn-info" value="Datum">
                                </form>

                                    <!-- slaganje rastuće i padajuće -->

                                <?php if($value === 'asc'): ?>

                                <form method="post">
                                    <input type="hidden" name="value" value="desc">
                                    <input type="hidden" name="group" value="<?php echo $group; ?>">
                                    <input type="submit" class="btn btn-primary" value="Rastuće">
                                </form>

                                <?php else: ?>

                                <form method="post">
                                    <input type="hidden" name="value" value="asc">
                                    <input type="hidden" name="group" value="<?php echo $group; ?>">
                                    <input type="submit" class="btn btn-primary" value="Padajuće">
                                </form>

                                <?php endif; ?>

                                <!-- tablice s podacima -->
                                <table class="table" style="color: whitesmoke ;">
                                    <tr>
                                        <th>Naziv:</th>
                                        <th>Napravljena:</th>
                                        <th>Broj zadataka:</th>
                                        <th>Broj nedovršenih zadataka</th>
                                        <th></th>
                                        <th></th>
                                    </tr>



                                    <!-- drugi dio "paginacije" -> broj stranica i podaci za izlistanje -->
                                    <?php if(isset($_COOKIE['user_id'])): ?>
                                    <?php broj_stranica_lista($_COOKIE['user_id'], $npp); ?>
                                    <?php $total_pages = $pages; ?>
                                        
                                    <!-- sve korisnikove liste -->
                                    <?php  dohvat_listi($_COOKIE['user_id'], $page, $npp, $group, $value); ?>

                                    <!-- kraj drugog dijela "paginacije" -->

                                        <?php foreach ($liste as $lista): ?>
                                            <tr>
                                                <td><?php echo $lista->naziv_liste; ?></td>
                                                <td><?php echo $lista->datum_unosa; ?></td>
                                                <td><?php echo lista_zadaci($lista->to_do_id); ?></td>
                                                <td><?php echo nedovrseni_zadaci($lista->to_do_id); ?></td>
                                                <!-- forma za pregled liste -->
                                                <td>
                                                    <form action="lista.php" method="get">
                                                        <input type="hidden" name="to_do_id" value="<?php echo $lista->to_do_id; ?>">
                                                        <input type="submit" class="btn btn-primary" value="Pregled">
                                                    </form>
                                                </td>
                                                <!-- kraj forme za pregled liste -->
                                                <!-- forma za brisanje liste -->
                                                <td>
                                                    <form action="rad_s_listama.php" method="post">
                                                        <input type="hidden" name="to_do_id" value="<?php echo $lista->to_do_id; ?>">
                                                        <input type="submit" class="btn btn-danger" value="Brisanje">
                                                    </form>
                                                </td>
                                                <!-- kraj forme za brisanje liste -->
                                            </tr>

                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </table>
                                <!-- kraj prikaza lista -->

                                <!-- Treći dio "paginacije -> "kretanje" kroz podatke " -->

                                <?php if(isset($_COOKIE['user_id'])): ?>

                            <div class="text-center">
                                <div class="pagination">
                                    <ul class="pagination">
                                        <li><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=1">First</a></li>
                                        <li class="arrow"><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $page-1; ?>">&laquo;</a></li>
                                        <?php
                                        for($i=1; $i<=$total_pages;$i++):
                                            if($i-5<=$page && $i+5>=$page):
                                                ?>
                                                <li <?php if($i==$page){ echo "class=\"current\""; } ?>>
                                                    <a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                            <?php endif; endfor;?>
                                        <li class="arrow"><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $page < $total_pages ? $page+1 : $page ; ?>">&raquo;</a></li>
                                        <li ><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $total_pages; ?>">Last</a></li>
                                    </ul>
                                </div>
                            </div>

                                <?php endif; ?>

                                <!-- kraj "paginacije" -->
                                <!-- Gumb za novu listu -->

                                <?php if(isset($_COOKIE['user_id'])): ?>

                                    <div class="text-center">
                                        <a href="#new"><button type="button" class="btn btn-info">Dodajte novu listu</button></a>
                                    </div>

                                <?php endif; ?>
                                <!-- kraj - Gumb za novu listu -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- uključujemo izbornik -->
    <?php include 'menu.php'; ?>
            
        </div>
    </div>
</header>
<!-- Header kraj -->