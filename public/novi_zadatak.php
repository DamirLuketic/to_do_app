<?php
if(!isset($_COOKIE['user_id']))
{
    header('location: index.php');
}
?>
<!-- Početak dijela za novi zadatak -->
<section id="novi_zadatak">
    <div class="welcome_section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <!-- Opis -->
                        <?php if(isset($_COOKIE['user_id'])): ?>
                            <div class="section-title">
                                <h2>Napravite novi zadatak:</h2>
                            </div>
                        <?php endif; ?>
                        <!-- Kraj opisa -->
                    </div>
                    <?php if(isset($_COOKIE['user_id'])): ?>

                        <!-- dio za unos novog zadatka -->

                        <div class="row">

                                <input type="hidden" id="napravi_to_do_id" value="<?php echo $podaci_liste->to_do_id; ?>">

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                                    <div class="welcome_part wow fadeInLeft">
                                        <div class="text-center">
                                            <input required="required" type="text" id="napravi_naziv">
                                        </div>
                                        <h2>Naziv zadatka</h2>
                                    </div>
                                </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="welcome_part wow fadeInLeft">
                                    <div class="text-center">

                                        <?php
                                        $prioritet = $GLOBALS['con']->query(' select * from prioriteti ');
                                        $prioriteti = $prioritet->fetchAll(PDO::FETCH_OBJ);
                                        ?>

                                        <select required="required" id="napravi_prioritet">

                                            <?php foreach ($prioriteti as $prioritet): ?>

                                                <option value="<?php echo $prioritet->prioritet_id; ?>"><?php echo $prioritet->naziv; ?></option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>
                                    <h2>Prioritet</h2>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="welcome_part wow fadeInLeft">
                                    <div class="text-center">
                                        <input type="datetime" required="required" id="napravi_rok" placeholder="1980/12/30 12:59:59">
                                    </div>
                                    <h2>Rok</h2>
                                </div>
                            </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                                    <div class="welcome_part wow fadeInLeft">
                                        <div class="text-center">
                                            <input type="submit" value="Napravi" class="btn btn-default" id="napravi_potvrda">
                                        </div>
                                        <h2>Napravi</h2>
                                    </div>
                                </div>

                        </div>
                        <!-- dio za unos novog zadatka -> kraj -->

                        <!-- ako korisnik nije prijavljen prikazujemo sljedeči tekst -->
                    <?php else: ?>

                        <div class="text-center">
                            <h1>Za daljnji rad potrebna je prijava\registracija</h1>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Kraj -->

<!-- Skripta za pravljenje zadatka -->
<script type="text/javascript">

    // funkcija za provijeru datuma i sati
    function datum_sat(txtDate)
    {
        var trenutno = txtDate;
        if(trenutno == '')
        return false;
        //Declare Regex
        var uvjet = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})(\ |-)(\d{1,2})(\:|-)(\d{1,2})(\:|-)(\d{1,2})$/;
        var provijera = trenutno.match(uvjet); // provjera unosa
        if (provijera == null)
        return false;
        //Checks for yyyy/mm/dd hh:mm:ss format.
        dt_godina   = provijera[1];
        dt_mjesec   = provijera[5];
        dt_dani     = provijera[7];
        dt_sati     = provijera[9];
        dt_minute   = provijera[11];
        dt_sekunde  = provijera[13]

        // provjera datuma
        if (dt_mjesec < 1 || dt_mjesec > 12)
        return false;
    else if (dt_dani < 1 || dt_dani> 31)
        return false;
    else if ((dt_mjesec==4 || dt_mjesec==6 || dt_mjesec==9 || dt_mjesec==11) && dt_dani ==31)
        return false;
    else if (dt_mjesec == 2)
        {
            var isleap = (dt_godina % 4 == 0 && (dt_godina % 100 != 0 || dt_godina % 400 == 0));
            if (dt_dani> 29 || (dt_dani ==29 && !isleap))
            return false;
        }
        // provjera sati
        if (dt_sati < 1 || dt_sati > 24)
            return false;
        else if (dt_minute < 1 || dt_minute> 60)
            return false;
        else if (dt_sekunde < 1 || dt_sekunde> 60)
            return false;

        // ako su datum i sati ispravno unesene nastavljamo dalje
        return true;
    }

    // funkcija za provijeru datuma i sati -> kraj

    // provijerimo unjeti datum, ako je ispravan nastavljamo s unosom u bazu
       $(document).ready(function(){

          $('#napravi_potvrda').click(function(){

            var txtVal =  $('#napravi_rok').val();

              // ako su unjeti datum i sati ispravni
            if(datum_sat(txtVal)) {

                     var napravi_to_do_id        = $('#napravi_to_do_id').val();
                     var napravi_naziv           = $('#napravi_naziv').val();
                     var napravi_prioritet       = $('#napravi_prioritet').val();
                     var napravi_rok             = $('#napravi_rok').val();

              // ako su svi podaci unjeti nastavlja se postupak
              if(napravi_to_do_id != '' && napravi_naziv != '' && napravi_prioritet != '' && napravi_rok != '') {

                  $.post('rad_s_zadacima.php', {
                      napravi_to_do_id: napravi_to_do_id,
                      napravi_naziv: napravi_naziv,
                      napravi_prioritet: napravi_prioritet,
                      napravi_rok: napravi_rok
                  }, function (data) {
                  });
                      // obaviještavamo korisnika da je zadatak napravljen
                  var upit = prompt('Zadatak je napravljen. ' +
                      '              Želite li ostati na listi (\"da\")');

                  if(upit === 'da')
                  {
                      window.location.replace('lista.php?to_do_id=' + napravi_to_do_id);
                  }else{
                      window.location.replace('index.php');
                  };

              }else{
                  // obavijest o ne unjetim podacima
                  alert('Molimo unesite sve podatke.');
              }

            }
                // ako su datum\satu netocni
            else
            alert('Netočan datum\\\sati');
        });
       });
</script>
<!-- Skripta za pravljenje zadatka -> kraj -->

