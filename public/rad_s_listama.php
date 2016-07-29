<?php

include_once 'functions.php';

// brisanje liste
if(isset($_POST['to_do_id']) && !empty($_POST['to_do_id']))
{
    brisanje_liste($_POST['to_do_id']);
}

// pravljenje nove liste
if(isset($_POST['naziv']) && isset($_POST['korisnik_id']) &&
    !empty($_POST['naziv']) && !empty($_POST['korisnik_id']))
{
    napravi_to_do($_POST['naziv'], $_POST['korisnik_id']);
};

 ?>