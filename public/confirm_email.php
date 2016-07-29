<?php
include 'functions.php';

if(isset($_GET['email']) && isset($_GET['email_kod']) &&
        !empty($_GET['email']) && !empty($_GET['email_kod']))
{
    potvrda_registracije($_GET['email'], $_GET['email_kod']);
};

?>