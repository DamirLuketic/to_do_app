<?php

include_once 'config.php';

// funkcija za tvorbu izbornika
function izbornik()
{

        if(isset($_COOKIE['user_id']))
        {
            $pages = array
            (
                'index.php'           => 'Liste',
                'log_out.php'         => 'Odjava'
            );
        }else
        {
            $pages = array
            (
                'index.php'     => 'Liste',
                'register.php'  => 'Registracija',
                'login.php'     => 'Prijava',
            );
        };

        foreach ($pages as $address => $name)
        {
            echo '<li><a href="' . $address . '">' . $name . '</a></li>';
        };
};

// funkcija za registraciju
function registracija($ime, $prezime, $email, $lozinka, $ponovljena_lozinka = '')
{
    //provijerimo da li su sva polja unjeta
    if(isset($ime) && isset($prezime) && isset($email) && isset($lozinka) && isset($ponovljena_lozinka) &&
        !empty($ime) && !empty($prezime) && !empty($email) && !empty($lozinka) && !empty($ponovljena_lozinka)
    )
    {
        // provjerimo da li su lozinka i ponovljena lozinka istovjetne
        if($lozinka === $ponovljena_lozinka)
        {
            // provjerimo da li e-mail već postoji u bazi
            $provijeri_email = $GLOBALS['con']->prepare(' select email from korisnici where email = :email ');
            $provijeri_email->execute(array(
                'email' => $email
            ));
            $provijeri_email = $provijeri_email->fetch(PDO::FETCH_OBJ);

                // ako je e-mail u bazi registracija se prekida i stvara se nova poruka za korisnika
                if($provijeri_email == true)
                {
                    return $_SESSION['msg'] = 'E-mail je već u uporabi.';
                };

            // postavljamo email kod za provjeru podataka
            $email_kod = rand();

            $register = $GLOBALS['con']->prepare(' insert into korisnici(email, ime, prezime, lozinka, datum_registracije, email_kod) values 
                                                                        (:email, :ime, :prezime, md5(:lozinka), current_date(), :email_kod)
                                              ');
            $register->execute([
                ':email'        => $email,
                ':ime'          => $ime,
                ':prezime'      => $prezime,
                ':lozinka'      => $lozinka,
                ':email_kod'    => $email_kod
            ]);

            // poruka za potrebom potvrde e-mail-a
            $_SESSION['msg'] = 'Molimo potvrdite regitraciju linkom u vašem e-mailu';

            //e-mail za potvrdu

            $email_msg = 'Link za potvrdu registracije: 
            http://consilium-europa.com/pages/to_do_app/public/confirm_email.php?email=' . $email . '&email_kod=' . $email_kod;

            mail($email, 'Potvrda registracije', $email_msg);
            

        }else
        {
            // ako lozinka i ponovljena lozinka ne odgovaraju pravimo poruku za korisnika
            $_SESSION['msg'] = 'Lozinka i ponovljena lozinka ne odgovaraju';
        };
    };
};

// funkcija za potvrdu registracije
function potvrda_registracije($email, $email_kod)
{
    $test = $GLOBALS['con']->prepare(' select email from korisnici where email = :email and email_kod = :email_kod');
    $test->execute([
        ':email'     => $email,
        ':email_kod' => $email_kod
    ]);

    $rezultat = $test->fetch(PDO::FETCH_OBJ);

    if(isset($rezultat) && !empty($rezultat))
    {
        $potvrda = $GLOBALS['con']->prepare(' update korisnici set status = 1, email_kod = 0, datum_zadnjeg_log_in = current_date() where email = :email');
        $potvrda->execute([
            ':email' => $email
        ]);

        $dohvat = $GLOBALS['con']->prepare(' select korisnik_id from korisnici where email = :email ');
        $dohvat->execute([
            ':email' => $email
        ]);
        $korisnikov_id = $dohvat->fetch(PDO::FETCH_OBJ);

        setcookie('user_id', $korisnikov_id->korisnik_id, time() + 86400);

        header('location: index.php');


    }else
    {
      echo '<h1>Neuspjela potvrda, <a href="register.php">probajte ponovno</a></h1>';
    };
};

// funkcija za prijavu
function log_in($email, $lozinka)
{
    $korisnik = $GLOBALS['con']->prepare(' select * from korisnici where email = :email and lozinka = md5(:lozinka) and status = 1 ');
    $korisnik->execute([
        ':email'     =>     $email,
        ':lozinka'   =>     $lozinka
    ]);

    $korisnik_id = $korisnik->fetch(PDO::FETCH_OBJ);

    // ako je prijava uspješna
    if(isset($korisnik_id->korisnik_id))
    {
        // postavljamo zadnji datum prijave
        $zadnji_log_in = $GLOBALS['con']->prepare(' update korisnici set datum_zadnjeg_log_in = current_date() where email = :email');
        $zadnji_log_in->execute([
            ':email' => $email
                        ]);

        // postavi korisnički "Cookie" za tjedan dana
        setcookie('user_id', $korisnik_id->korisnik_id, time() + 86400);

        header('location: index.php');
    };
};

// funkcija za prikaz grešaka pri prijavi
function login_try()
{
    if(!isset($_POST['email']) || !isset($_POST['lozinka']) || empty($_POST['email']) || empty($_POST['lozinka']))
    {
        echo 'Unesite e-mail i lozinku';
    }else
    {
        if(!isset($_COOKIE['user_id']))
        {
            echo 'Pogrešan e-mail\lozinka';
        };
    };
};

// funkcija za odjavu
function log_off()
{
    // postavio sam varijablu "$past" na prošlo vrijeme
    $past = time() - 3600;

    // poništavamo sve "Cookies"
    foreach ( $_COOKIE as $key => $value )
    {
        setcookie( $key, $value, $past);
    }

    header('location: index.php');
};

// funkcija za računjanje broja stranica listi -> kod "paginacije"
function broj_stranica_lista($korisnik_id, $npp)
{
    global $pages;

    $total = $GLOBALS['con']->prepare(' select count(naziv) from to_do where korisnik_id = :korisnik_id ');
    $total->execute([
        ':korisnik_id' => $korisnik_id
    ]);
    $pages = ceil($total->fetchColumn() / $npp);
    
    return $pages;
}

// funkcija za dohvat podataka lista-> kod "paginacije"
function dohvat_listi($korisnik_id, $page, $npp, $group, $value)
{
    global $liste;
    
    $liste = $GLOBALS['con']->prepare('         select to_do_id, korisnik_id, naziv as "naziv_liste", datum_unosa 
                                                from to_do
                                                where korisnik_id = :korisnik_id
                                                order by ' . $group . ' ' . $value . '
                                                limit '  . (($page * $npp / $npp)<=0 ? 0 : ($page * $npp - $npp)) . ', ' . $npp
    );
    $liste->execute([
        ':korisnik_id' => $korisnik_id
    ]);

    $liste = $liste->fetchAll(PDO::FETCH_OBJ);
    
    return $liste;
}

// funkcija za određivanje zadataka u listi
function lista_zadaci($to_do_id)
{
    global $zadaci;

    $svi = $GLOBALS['con']->prepare(' select count(status) from zadaci where lista_id = :lista_id ');
    $svi->execute([
        ':lista_id' => $to_do_id
    ]);
    $zadaci = $svi->fetchColumn();

    return $zadaci;
}

// funkcija za određivanje nedovršenih zadataka
function nedovrseni_zadaci($to_do_id)
{
    global $nedovrseni_zadaci;

    $nedovrseno = $GLOBALS['con']->prepare(' select count(status) from zadaci where lista_id = :lista_id and status = 0 ');
    $nedovrseno->execute([
        ':lista_id' => $to_do_id
    ]);
    $nedovrseni_zadaci = $nedovrseno->fetchColumn();

    return $nedovrseni_zadaci;
}

// funkcija za brisanje liste
function brisanje_liste($to_do_id)
{
    $brisanje = $GLOBALS['con']->prepare(' delete from to_do where to_do_id = :to_do_id ');
    $brisanje->execute([
        ':to_do_id' => $to_do_id
    ]);

    header('location: index.php');
}

// funkcija za pravljenje nove liste
function napravi_to_do($naziv, $korisnik_id)
{
    $napravi = $GLOBALS['con']->prepare(' insert into to_do (korisnik_id, naziv, datum_unosa) values 
                                        (:korisnik_id, :naziv, current_date())
                                      ');
     $napravi->execute([
                    ':korisnik_id' => $korisnik_id,
                    ':naziv'    => $naziv,
                    ]);

    $id_nove_liste = $GLOBALS['con']->lastInsertId();

    return header('location: lista.php?to_do_id=' . $id_nove_liste);
}

// funkcija za dohvat podataka liste
function podaci_lista($to_do_id)
{
    global $podaci_liste;

    $lista_podaci = $GLOBALS['con']->prepare(' select * from to_do where to_do_id = :to_do_id ');
    $lista_podaci->execute([
        ':to_do_id' => $to_do_id
                        ]);
    $podaci_liste = $lista_podaci->fetch(PDO::FETCH_OBJ);

    return $podaci_liste;
}

// funkcija za broj stranica zadataka
function broj_stranica_zadataka($to_do_id, $npp)
{
    global $pages;

    $total = $GLOBALS['con']->prepare(' select count(naziv) from zadaci where lista_id = :to_do_id ');
    $total->execute([
        ':to_do_id' => $to_do_id
    ]);
    $pages = ceil($total->fetchColumn() / $npp);

    return $pages;
}

// funkcija za dohvat zadataka u listi
function zadaci_liste($to_do_id, $page, $npp, $group, $value)
{
    global $zadaci;
    
    $lista_zadaci = $GLOBALS['con']->prepare('  select a.naziv, a.status, a.prioritet_id, a.rok , b.naziv as "naziv_prioriteta", a.zadatak_id 
                                                from zadaci as a inner join prioriteti as b on a.prioritet_id = b.prioritet_id 
                                                where lista_id = :to_do_id
                                                order by ' . $group .  ' ' . $value .'
                                                limit '  . (($page * $npp / $npp)<=0 ? 0 : ($page * $npp - $npp)) . ', ' . $npp
                                                );
    $lista_zadaci->execute([
        ':to_do_id' => $to_do_id
    ]);
    $zadaci = $lista_zadaci->fetchAll(PDO::FETCH_OBJ);

    return $zadaci;
}

// funkcija za brisanje zadatka
function brisanje_zadatka($zadatak_id)
{
    $brisanje = $GLOBALS['con']->prepare(' delete from zadaci where zadatak_id = :zadatak_id ');
    $brisanje->execute([
                        'zadatak_id' => $zadatak_id
                        ]);
}

// funkcija za pravljenje zadatka
function napravi_zadatak($to_do_id, $naziv, $prioritet_id, $rok)
{
    $napravi = $GLOBALS['con']->prepare(' insert into zadaci (lista_id, naziv, prioritet_id, rok) values
                                                            (:lista_id, :naziv, :prioritet_id, :rok)
                                       ');
    $napravi->execute([
        ':lista_id'     => $to_do_id,
        ':naziv'        => $naziv,
        ':prioritet_id' => $prioritet_id,
        ':rok'          => $rok
    ]);
}

// funkcija za dohvat podataka o zadatku
function podaci_zadatak($zadatak_id)
{
    global $zadatak_podaci;

    $zadatak = $GLOBALS['con']->prepare(' select a.*, b.naziv as \'naziv_prioriteta\'
                                          from zadaci as a inner join prioriteti as b on a.prioritet_id = b.prioritet_id
                                          where a.zadatak_id = :zadatak_id ');
    $zadatak->execute([
        ':zadatak_id' => $zadatak_id
    ]);
    $zadatak_podaci = $zadatak->fetch(PDO::FETCH_OBJ);
    
    return $zadatak_podaci;
}

// funkcija za prilagodbu zadatka
function prilagodba_zadatka($zadatak_id, $naziv, $prioritet_id, $status, $datum_sat)
{
    $prilagodba = $GLOBALS['con']->prepare(' update zadaci set naziv = :naziv, prioritet_id = :prioritet_id, status = :status, rok = :rok where zadatak_id = :zadatak_id');
    $prilagodba->execute([
        ':naziv'        => $naziv,
        ':prioritet_id' => $prioritet_id,
        ':status'       => $status,
        ':rok'          => $datum_sat,
        ':zadatak_id'   => $zadatak_id
    ]);
}