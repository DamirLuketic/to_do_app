<?php

include 'functions.php';

if(isset($_POST['brisanje_zadatak_id']))
{
    brisanje_zadatka($_POST['brisanje_zadatak_id']);
}

if(isset($_POST['napravi_to_do_id']) && isset($_POST['napravi_naziv']) &&
            isset($_POST['napravi_prioritet']) && isset($_POST['napravi_rok']))
{
    napravi_zadatak($_POST['napravi_to_do_id'], $_POST['napravi_naziv'], $_POST['napravi_prioritet'], $_POST['napravi_rok']);
}
