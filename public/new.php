<!-- Početak dijela za novu listu -->
<section id="new">
    <div class="welcome_section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <!-- Opis -->
                        <?php if(isset($_COOKIE['user_id'])): ?>
                        <div class="section-title">
                            <h2>Napravite novu listu:</h2>
                            <p>Nakon pravljenja liste bit ćete preusmjereni na novu listu</p>
                        </div>
                        <?php endif; ?>
                        <!-- Kraj opisa -->
                    </div>
                    <?php if(isset($_COOKIE['user_id'])): ?>
                        
                    <div class="row">
                        <!-- forma za unos nove liste -->
                        <form action="rad_s_listama.php" method="post">

                            <input type="hidden" name="korisnik_id" value="<?php echo $_COOKIE['user_id']; ?>">

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="welcome_part wow fadeInLeft">
                                    <div class="text-center">
                                        <input type="text" name="naziv">
                                    </div>
                                    <h2>Naziv liste</h2>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="welcome_part wow fadeInLeft">
                                    <div class="text-center">
                                        <input type="submit" value="Napravi" class="btn btn-default">
                                    </div>
                                    <h2>Napravi</h2>
                                </div>
                            </div>
                        </form>
                        <!-- forma za unos nove liste -> kraj -->
                    </div>
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
<!-- kraj -->