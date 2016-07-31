<?php

include 'functions.php';

if(isset($_POST['brisanje_zadatak_id']))
{
    brisanje_zadatka($_POST['brisanje_zadatak_id']);
}

if(isset($_POST['napravi_to_do_id']) && isset($_POST['napravi_naziv']) && isset($_POST['napravi_rok_sati']) &&
            isset($_POST['napravi_prioritet']) && isset($_POST['napravi_rok']))
{
    // datum i sat spajamo u jedinstvenu vrijednost
    $datum_sat = $_POST['napravi_rok'] . ' ' . $_POST['napravi_rok_sati'] . ':00';

    napravi_zadatak($_POST['napravi_to_do_id'], $_POST['napravi_naziv'], $_POST['napravi_prioritet'], $datum_sat);
}

if(isset($_POST['novi_naziv']) && isset($_POST['novi_prioritet']) && isset($_POST['novi_status']) &&
    isset($_POST['novi_rok']) && isset($_POST['novi_rok_sati']) && isset($_POST['zadatak_id']))
{
    // datum i sat spajamo u jedinstvenu vrijednost
    $datum_sat = $_POST['novi_rok'] . ' ' . $_POST['novi_rok_sati'] . ':00';

    prilagodba_zadatka($_POST['zadatak_id'], $_POST['novi_naziv'], $_POST['novi_prioritet'], $_POST['novi_status'], $datum_sat);
}