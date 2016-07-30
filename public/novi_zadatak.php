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

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
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

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                                <div class="welcome_part wow fadeInLeft">
                                    <div class="text-center">
                                        <input type="date" required="required" id="napravi_rok" placeholder="1980/12/30 12:59:59">
                                    </div>
                                    <h2>Rok</h2>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                                <div class="welcome_part wow fadeInLeft">
                                    <div class="text-center">
                                        <input type="time" required="required" id="napravi_rok" placeholder="1980/12/30 12:59:59">
                                    </div>
                                    <h2>Rok</h2>
                                </div>
                            </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

$(document).ready(function(){

    $('#napravi_potvrda').click(function(){

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

            var upit = prompt('Zadataka je napravljen. ' +
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
    });
});
</script>
<!-- Skripta za pravljenje zadatka -> kraj -->

