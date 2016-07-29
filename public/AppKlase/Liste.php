<?php

namespace AppKlase;

class Liste
{

    protected $_con;
    protected $_page;
    protected $_npp;
    protected $_korisnik_id;
    
    public function __construct($con, $page, $npp, $korisnik_id)
    {
        $this->_con = $con;
        $this->_page = $page;
        $this->_npp = $npp;
        $this->_korisnik_id = $korisnik_id;
    }

    public function total_pages()
    {
        $t=new \stdClass();

        $total = $this->_con->prepare(' select count(naziv) from to_do where korisnik_id = :korisnik_id ');
        $total->execute([
            ':korisnik_id' => $this->_korisnik_id,
        ]);
        $pages = ceil($total->fetchColumn() / $this->_npp);

        $t->pages = $pages;

        return $t;
    }

    public function liste()
    {
        $liste = $this->_con->prepare('         select to_do_id, korisnik_id, naziv as "naziv_liste", datum_unosa from to_do
                                                where korisnik_id = :korisnik_id
                                                limit '  . (($this->_page * $this->_npp / $this->_npp)<=0 ? 0 : ($this->_page * $this->_npp - $this->_npp)) . ', ' . $this->_npp
        );
        $liste->execute([
            ':korisnik_id' => $this->_korisnik_id
        ]);

        return $liste = $liste->fetchAll(\PDO::FETCH_OBJ);
    }
}