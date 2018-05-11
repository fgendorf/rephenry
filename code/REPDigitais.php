<?php
/**
 * Classe que trata as digitais do relogio
 */
class REPDigitais extends REPEmpregado {

    public function __construct() {
        parent::__construct();
    }
/**
 * 
 * @return type
 */
    public function getTemplateDigital() {
        return $this->queryREP("00+RD+00+D]{$this->matriculaEmpregado}");
    }
/**
 * 
 * @return array
 */
    public function listMatriculasDigitalRelogio() {

        $ret = $this->queryREP("00+RD+00+L]1000}0");
        $chkcalc = ord($this->checkSum(substr($ret, 3, -2)));
        $chkrcv = ord(substr($ret, -2, 1));
        if ($chkcalc == $chkrcv) {
            $proc = preg_split('/]/', substr(str_replace(chr(3), '', str_replace(chr(2), '', $ret)),0,-1));
            array_shift($proc);
            
        }
        return $proc;
    }

}
