<?php

class REPDigitais extends REPUsuario {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getTemplateDigital(){
        return $this->queryREP("00+RD+00+D]{$this->matriculaUsuario}");
    }

}
