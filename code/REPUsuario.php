<?php

class REPUsuario extends REPHenry {
    protected $pisUsuario;
    protected $nomeUsuario;
    protected $matriculaUsuario;
    protected $biometriaUsuario=1;



    public function __construct() {
        parent::__construct();
    }
    
    public function cadastraUsuario(){
        return $this->queryREP("00+EU+00+1+I[{$this->pisUsuario}[{$this->nomeUsuario}[{$this->biometriaUsuario}[1[{$this->matriculaUsuario}");
    }
    
    public function listaUsuarios(){
        
    }
    
    
}