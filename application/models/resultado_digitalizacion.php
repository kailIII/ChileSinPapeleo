<?php

Class ResultadoDigitalizacion extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn('id');
        $this->hasColumn('ficha_id');
        $this->hasColumn('mensaje');
    }

    public function setUp() {
        parent::setUp();
        $this->actAs('Timestampable');
    }

}

?>
