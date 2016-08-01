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
                                <!-- zovemo funkciju za dohvat podataka o zadatku -> dohvaćamo podatke u globalnu varijablu "$zadatak_podaci" -->
                                <?php podaci_zadatak($_POST['zadatak_id']); ?>
                                <!-- zovemo funkciju za dohvat podataka o zadatku -> kraj -->
                                <!-- Site naslov start-->
                                <div class="site_title">
                                    <h1>Prilagodba zadatka:</h1>
                                </div>
                                <!-- Site naslov end-->
                                <!-- Prikaz podataka i forma za prilagodbu -->

                                <table class="table" style="color: whitesmoke;">
                                  <tr>
                                      <th>Naziv:</th>
                                      <th>Prioritet:</th>
                                      <th>Status:</th>
                                      <th>Rok:</th>
                                  </tr>
                                  <tr>
                                      <td><input class="form-control" type="text" id="novi_naziv" value="<?php echo $zadatak_podaci->naziv; ?>"></td>
                                      <td>
                                          <div class="form-control">
                                          <!-- dohvat podataka prioritetima -> prikazjje se trenutno aktivni prioritet kao odabrani -->
                                              <?php
                                              $prioritet = $GLOBALS['con']->query(' select * from prioriteti ');
                                              $prioriteti = $prioritet->fetchAll(PDO::FETCH_OBJ);
                                              ?>
                                              <select required="required" id="novi_prioritet">
                                                  <?php foreach ($prioriteti as $prioritet): ?>
                                                      <?php if($zadatak_podaci->prioritet_id === $prioritet->prioritet_id): ?>
                                                          <option value="<?php echo $prioritet->prioritet_id; ?>" selected="selected"><?php echo $prioritet->naziv; ?></option>
                                                      <?php else: ?>
                                                          <option value="<?php echo $prioritet->prioritet_id; ?>"><?php echo $prioritet->naziv; ?></option>
                                                      <?php endif; ?>
                                                  <?php endforeach; ?>
                                              </select>
                                              </div>
                                          <!-- dohvat podataka prioritetima -> kraj -->
                                      </td>
                                      <!-- mijenjanje statusa -> prikaz ovisi o trenutnom stanju -->
                                      <td>
                                          <div class="form-control">
                                              <select required="required" id="novi_status">
                                                  <?php if($zadatak_podaci->status == 0): ?>
                                                      <option id="novi_status" value="1">Riješen</option>
                                                      <option id="novi_status" value="0" selected="selected">Neriješen</option>
                                                  <?php else: ?>
                                                      <option id="novi_status" value="1" selected="selected">Riješen</option>
                                                      <option id="novi_status" value="0">Neriješen</option>
                                                  <?php endif; ?>
                                              </select>
                                          </div>
                                      </td>
                                      <!-- mijenjanje statusa -> kraj-->
                                      <?php
                                      // odvajamo datum od sata -> za daljnji prikaz i prilagodbu
                                      $datum_sat = explode(' ', $zadatak_podaci->rok);
                                      $datum = $datum_sat[0];
                                      $sat = substr($datum_sat[1], 0, 5);
                                      ?>
                                      <td style="color: #3c3c3c">
                                                      <input type="date" required="required" value="<?php echo $datum; ?>" id="novi_rok">
                                                      <input type="time" required="required" value="<?php echo $sat; ?>" id="novi_rok_sati" value="00:00">
                                      </td>
                                  </tr>
                                    <!-- dio za prilagodbu podataka -->
                                </table>
                                <input type="hidden" id="zadatak_id" value="<?php echo $zadatak_podaci->zadatak_id; ?>">
                                <div class="text-center">
                                    <input type="submit" value="Prilagodi" class="btn btn-default" id="novi_potvrda">
                                </div>
                                <br />
                                <!-- prikaz podataka i forma za prilagodbu / kraj -->
                                <!-- povratak na prethodnu stranicu -->
                                <div class="text-center">
                                     <a href="lista.php?to_do_id=<?php echo $_POST['to_do_id']; ?>&page=<?php echo $_POST['page']; ?>"><button class="btn btn-info">Povratak na listu</button></a>
                                </div>
                                <!-- povratak na prethodnu stranicu -> kraj -->
                                
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

<!-- Skripta za prilagodbu zadatka -->
<script type="text/javascript">

    $(document).ready(function(){

        $('#novi_potvrda').click(function(){

            var zadatak_id           = $('#zadatak_id').val();
            var novi_naziv           = $('#novi_naziv').val();
            var novi_prioritet       = $('#novi_prioritet').val();
            var novi_status          = $('#novi_status').val();
            var novi_rok             = $('#novi_rok').val();
            var novi_rok_sati        = $('#novi_rok_sati').val();

            // ako su svi podaci unjeti nastavlja se postupak
            if(novi_naziv != '' && novi_prioritet != '' && novi_status != '' && novi_rok != '' && novi_rok_sati != '' && zadatak_id != '') {

                $.post('rad_s_zadacima.php', {
                    zadatak_id : zadatak_id,
                    novi_naziv: novi_naziv,
                    novi_prioritet: novi_prioritet,
                    novi_status: novi_status,
                    novi_rok: novi_rok,
                    novi_rok_sati : novi_rok_sati
                }, function (data) {
                });

                // obaviještavamo korisnika da je zadatak prilagođen

                alert('Zadataka je prilagođen');

            }else{
                // obavijest o ne unjetim podacima
                alert('Molimo unesite sve podatke.');
            }
        });
    });
</script>
<!-- Skripta za prilagodbu zadatka -> kraj -->
</body>
</html>