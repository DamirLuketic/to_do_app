drop database if exists to_do_app;
create database to_do_app charset utf8;
use to_do_app;

/* tablica za korisnike */
create table korisnici(
korisnik_id int not null primary key auto_increment,
email varchar(250) not null,
ime varchar(250) not null,
prezime varchar(250) not null,
lozinka varchar(250) not null,
datum_registracije date not null,
datum_zadnjeg_log_in date,
status boolean not null default 0,
email_kod int
);

/* dodavanje jedinstvenog indexa za e-mail */
create unique index ui1 on korisnici(email);

/* osnovne vrijednosti za tablicu korisnika -> za probu -> za lozinku ću koristiti "md5" zaštitu
	te su osnovne lozinke postavljene s "md5" zaštitom
*/
insert into korisnici(email, ime, prezime, lozinka, datum_registracije, status, email_kod) values
/* 1  -> izvorna lozinka -> "pass11" */
('luketic.damir@gmail.com', 'Damir', 'Luketić', '0102812fbd5f73aa18aa0bae2cd8f79f', curdate(), 1, 0),
/* 2  -> izvorna lozinka -> "pass22" */
('abc123@net.hr', 'Damir->Test', 'Luketić->test', 'b9974191c2e2806abb0ed0fe229ca0f6', curdate(), 1, 0);

/* tablica za liste */
create table to_do(
to_do_id int not null primary key auto_increment,
korisnik_id int not null,
naziv varchar(100) not null,
datum_unosa date not null
);

/* dodavanje vanjskog ključa tablici za liste */
alter table to_do add foreign key (korisnik_id) references korisnici(korisnik_id); 

/* dodavanje osnovnih vrijednosti za liste -> za probu */
insert into to_do(korisnik_id, naziv, datum_unosa) values
/* 1 */
(1, 'Lista 1', '2016-09-25'),
/* 2 */
(1, 'Lista 2', '2016-06-28'),
/* 3 */
(1, 'Lista 3', '2016-07-09'),
/* 4 */
(2, 'Lista 4', '2016-08-28'),
/* 5 */
(2, 'Lista 5', '2016-08-17');

/* tablica za prioritete zadatka */
create table prioriteti(
prioritet_id int not null primary key auto_increment,
naziv varchar(10)
);

/* osnovne vrijednosti za tablicu prioriteta */
insert into prioriteti(naziv) values
/* 1 */
('low'),
/* 2 */
('normal'),
/* 3 */
('high');

/* tablica za zadatke */
create table zadaci(
zadatak_id int not null primary key auto_increment,
lista_id int not null,
naziv varchar(250) not null,
prioritet_id int not null,
status boolean default 0,
rok datetime not null
);

/* dodavanje vanjskih ključeva tablici za zadatke */
alter table zadaci add foreign key (prioritet_id) references prioriteti(prioritet_id);
/* brišemo sve zadatke s liste, ako je lista obrisana */
alter table zadaci add foreign key (lista_id) references to_do(to_do_id) on delete cascade;

/* osnovne vrijednosti zadataka -> za probu */
insert into zadaci(naziv, lista_id, prioritet_id, rok, status) values
/* 1 */
('Servis računala', 1, 1, '2016-06-28 06:00:00', 0),
/* 2 */
('Servis printera', 1, 2, '2015-08-28 06:00:00', 1),
/* 3 */
('Popravak hardwara', 1, 2, '2016-10-28 12:00:00', 0),
/* 4 */
('Korisnička podržka', 2, 3, '2016-09-28 06:00:00', 0),
/* 5 */
('Radio oglašavanje', 2, 2, '2015-10-28 06:00:00', 1),
/* 6 */
('TV oglašavanje', 3, 2, '2016-11-16-18 06:00:00', 1),
/* 7 */
('Internet oglašavanje', 3, 2, '2016-09-26 06:00:00', 1),
/* 8 */
('Panelno oglašavanje', 4, 2, '2015-09-23 06:00:00', 0),
/* 9 */
('Tvorba branda', 4, 2, '2016-09-24 06:00:00', 1),
/* 10 */
('Marketing', 4, 2, '2015-09-22 06:00:00', 0),
/* 11 */
('Analiza podataka', 5, 2, '2016-10-28 06:00:00', 0),
/* 12 */
('Istraživanje tržišta', 5, 2, '2016-11-28 06:00:s00', 1);

