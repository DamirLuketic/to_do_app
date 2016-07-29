<?php
if(!isset($_COOKIE['user_id']))
{
    header('location: index.php');
}
?>
<?php include_once 'config.php'; ?>
<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <?php include 'header.php'; ?>
<!-- dodajemo skriptu za AJAX i JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Skripta za brisanje zadatka -->
    <script type="text/javascript">

        $(document).ready(function(){

            $('#brisanje_potvrda').click(function(){

                var rec = $('#brisanje_zadatak_id').val();

                $.post('rad_s_zadacima.php', {brisanje_zadatak_id:rec}, function(data){

                });

                $('#brisanje_potvrda').val('Obrisano');

            });

        });

    </script>
    <!-- Skripta za brisanje zadatka -> kraj -->
<body >

<!-- dohvat podataka liste -> kao "$podaci_liste" -->
<?php podaci_lista($_GET['to_do_id']); ?>
<!-- kraj dohvata podataka liste -->

<header id="header_part">
    <div class="header_part" id="head">
        <div class="overlay">
            <div class="start_part">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <!-- Logo Start -->
                                <div class="site_logo">
                                    <a href="#" title=""><img height="80" src="images/logo.png" alt="" title=""/></a>
                                </div>
                                <!-- Logo End-->
                                <!-- Site naslov start-->
                                <div class="site_title">
                                    <h1>Lista \ <?php echo $podaci_liste->naziv; ?></h1>
                                </div>
                                <!-- Site naslov end-->


                                <!-- prikaz podataka liste -->

                                <table class="table" style="color: whitesmoke;">
                                    <tr>
                                        <th>Naziv:</th>
                                        <th>Napravljena:</th>
                                        <th>Broj zadataka:</th>
                                        <th>Broj nedovršenih zadataka</th>
                                        <th>Napredak (%):</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $podaci_liste->naziv; ?></td>
                                        <td><?php echo $podaci_liste->datum_unosa; ?></td>
                                        <td><?php echo lista_zadaci($podaci_liste->to_do_id); ?></td>
                                        <td><?php echo nedovrseni_zadaci($podaci_liste->to_do_id); ?></td>
                                        <!-- prikaz riješenosti u postotku -> varijeble su dobivene iz funkcija za dohvat broja zadataka u listi -->
                                        <td><?php echo $zadaci == 0 ? 'Nema zadataka' : number_format(100 - ($nedovrseni_zadaci / $zadaci) * 100); ?></td>
                                        <!-- forma za brisanje liste -->
                                        <td>
                                            <form action="rad_s_listama.php" method="post">
                                                <input type="hidden" name="to_do_id" value="<?php echo $podaci_liste->to_do_id; ?>">
                                                <input type="submit" class="btn btn-danger" value="Brisanje">
                                            </form>
                                        </td>
                                        <!-- kraj forme za brisanje liste -->
                                  </tr>
                                </table>

                                <hr />
                                <br />
                                <!-- kraj prikaza podataka liste -->

                                <!-- Naslov dijela za zadatke / start-->
                                <div class="text-center" style="color: whitesmoke;">
                                    <h1>Zadaci:</h1>
                                </div>
                                <!-- Naslov dijela za zadatke / start-->

                                <!-- prikaz zadataka -->

                            <!-- "Paginacija" za zadatke -> prvotni uvjeti -->

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
                            $npp = 4;

                            // vrjednosti za grupiranje pretraživanja
                            if(isset($_POST["group"]))
                            {
                                $group = $_POST["group"];
                            }else
                            {
                                $group = 1;
                            };

                            if(isset($_POST["value"]))
                            {
                                $value = $_POST["value"];
                            }else
                            {
                                $value = 'asc';
                            };

                            // priziv funkcije za određivanje broja stranica
                            broj_stranica_zadataka($podaci_liste->to_do_id, $npp);
                            $total_pages = $pages;

                            // dohvat svih zadataka liste preko funkcije -> vraća nam varijablu "$zadaci"
                            zadaci_liste($podaci_liste->to_do_id, $page, $npp, $group, $value);

                            ?>
                            <!-- "Paginacija" -> kraj prva dva dijela -->

                            <!-- slaganje po datim kriterijima -->

                            <form method="post">
                                <input type="hidden" name="group" value="1">
                                <input type="submit" class="btn btn-info" value="Naziv">
                            </form>

                            <form method="post">
                                <input type="hidden" name="group" value="3">
                                <input type="submit" class="btn btn-info" value="Prioritet">
                            </form>

                            <form method="post">
                                <input type="hidden" name="group" value="4">
                                <input type="submit" class="btn btn-info" value="Rok">
                            </form>

                            <form method="post">
                                <input type="hidden" name="group" value="2">
                                <input type="submit" class="btn btn-info" value="Status">
                            </form>

                            <!-- slaganje rastuće i padajuće -->

                            <?php if($value === 'asc'): ?>

                                <form method="post">
                                    <input type="hidden" name="value" value="desc">
                                    <input type="hidden" name="group" value="<?php echo $group; ?>">
                                    <input type="submit" class="btn btn-primary" value="Padajuće">
                                </form>

                            <?php else: ?>

                                <form method="post">
                                    <input type="hidden" name="value" value="asc">
                                    <input type="hidden" name="group" value="<?php echo $group; ?>">
                                    <input type="submit" class="btn btn-primary" value="Rastuće">
                                </form>

                            <?php endif; ?>

                            <!-- tablice s podacima -->


                                <table class="table" style="color: whitesmoke;">
                                  <tr>
                                      <th>Naziv:</th>
                                      <th>Prioritet:</th>
                                      <th>Rok:</th>
                                      <th>Status:</th>
                                      <th>Preostalo\Prekoračenje:</th>
                                      <th>Edit</th>
                                      <th></th>
                                  </tr>

                                    <?php foreach ($zadaci as $zadatak): ?>

                                  <tr>
                                      <td><?php echo $zadatak->naziv; ?></td>
                                      <td><?php echo $zadatak->naziv_prioriteta; ?></td>
                                      <td><?php echo date( "Y-m-d \\\ G:ia",strtotime($zadatak->rok)); ?></td>
                                      <td><?php echo $zadatak->status == 0 ? 'Neriješen' : 'Riješen'; ?></td>

                                      <td>
                                                    <?php
                                                    // ako je zadatak neriješen prikazujemo preostalo\prekoračeno vrijeme
                                                    if($zadatak->status == 0)
                                                    {
                                                          $now = new DateTime();
                                                        $future_date = new DateTime($zadatak->rok);

                                                        $interval = $future_date->diff($now);

                                                        if($now > $future_date) {
                                                            echo 'Prekoračeno : ' . $interval->format("%a days, %h hours, %i minutes, %s seconds");


                                                        }else
                                                        {
                                                            echo 'Preostalo : ' . $interval->format("%a days, %h hours, %i minutes, %s seconds");
                                                        }
                                                    }else
                                                    {
                                                        echo "Riješen";
                                                    }
                                          ?>
                                      </td>
                                      <td id="showmessage"></td>
                                      <td>
                                          <!-- dio za brisanje zadatka -->
                                            <!-- završni dio za brisanje zadatka-> potvrda -->
                                          <?php if(isset($_POST['brisanje_zadatak_id']) && $_POST['brisanje_zadatak_id'] === $zadatak->zadatak_id): ?>
                                              <input type="hidden" id="brisanje_zadatak_id" value="<?php echo $_POST['brisanje_zadatak_id']; ?>">
                                              <input type="submit" class="btn btn-danger" value="Potvrda" id="brisanje_potvrda">
                                          <?php else: ?>
                                              <!-- početni dio za brisanje zadatka -> dohvat "id" zadatka -->
                                              <form method="post">
                                                  <input type="hidden" name="brisanje_zadatak_id" value="<?php echo $zadatak->zadatak_id; ?>">
                                                  <input type="submit" class="btn btn-danger" value="Brisanje">
                                              </form>
                                          <?php endif; ?>
                                      </td>
                                  </tr>
                                    <?php endforeach; ?>
                                </table>

                            <!-- Treći dio "paginacije -> "kretanje" kroz podatke " -->

                            <?php if(isset($_COOKIE['user_id'])): ?>

                                <div class="text-center">
                                    <div class="pagination">
                                        <ul class="pagination">
                                            <li><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=1&to_do_id=<?php echo $podaci_liste->to_do_id; ?>">First</a></li>
                                            <li class="arrow"><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $page-1; ?>&to_do_id=<?php echo $podaci_liste->to_do_id; ?>">&laquo;</a></li>
                                            <?php
                                            for($i=1; $i<=$total_pages;$i++):
                                                if($i-5<=$page && $i+5>=$page):
                                                    ?>
                                                    <li <?php if($i==$page){ echo "class=\"current\""; } ?>>
                                                        <a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $i; ?>&to_do_id=<?php echo $podaci_liste->to_do_id; ?>"><?php echo $i; ?></a></li>
                                                <?php endif; endfor;?>
                                            <li class="arrow"><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $page < $total_pages ? $page+1 : $page ; ?>&to_do_id=<?php echo $podaci_liste->to_do_id; ?>">&raquo;</a></li>
                                            <li ><a href="<?php echo $_SERVER["PHP_SELF"] ?>?page=<?php echo $total_pages; ?>&to_do_id=<?php echo $podaci_liste->to_do_id; ?>">Last</a></li>
                                        </ul>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <!-- kraj "paginacije" -->

                                <!-- kraj prikaza zadataka -->

                            <!-- Gumb za novi zadatak -->

                            <?php if(isset($_COOKIE['user_id'])): ?>

                                <div class="text-center">
                                    <a href="#novi_zadatak"><button type="button" class="btn btn-info">Dodajte novi zadatak</button></a>
                                </div>

                            <?php endif; ?>
                            <!-- kraj - Gumb za novi zadatak -->

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

<?php include 'novi_zadatak.php'; ?>

</body>
</html>