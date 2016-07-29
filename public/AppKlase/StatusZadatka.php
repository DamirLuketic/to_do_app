<?php

namespace AppKlase;

class StatusZadatka
{

    protected $_con;
    protected $_lista_id;

    public function __construct($con, $lista_id)
    {
        $this->_con = $con;
        $this->_lista_id = $lista_id;
    }

    public function zadaci()
    {
        $t=new \stdClass();

        $svi = $this->_con->prepare(' select count(status) from zadaci where lista_id = :lista_id ');
        $svi->execute([
            ':lista_id' => $this->_lista_id
        ]);
        $ukupno_zadataka = $svi->fetchColumn();

        $t->ukupno = $ukupno_zadataka;

        return $t;
    }

    public function nedovrseni_zadaci()
    {
        $t=new \stdClass();

        // dohvacam podatak o riješenim i ne riješenim zadacima s liste
        $nedovrseno = $this->_con->prepare(' select count(status) from zadaci where lista_id = :lista_id and status = 0 ');
        $nedovrseno->execute([
            ':lista_id' => $this->_lista_id
        ]);
        $nedovrseni_zadaci = $nedovrseno->fetchColumn();

        $t->nedovrseni_zadaci = $nedovrseni_zadaci;

        return $t;
    }

}