<?php

/**
 * Classe de manipulação da data hora do relogio
 */
class REPDataeHora extends REPHenry {

    /**
     * 
     * @var string d/m/y 
     */
    protected $inicioHorarioVerao;

    /**
     *
     * @var string d/m/y
     */
    protected $fimHorarioVerao;

    /**
     *
     * @var string  d/m/y H:i:s
     */
    protected $horaCerta;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Setter horaCerta
     * @param string $horaCerta 
     */
    public function setHoraCerta($horaCerta = "") {
        if (empty($horaCerta)) {
            $this->horaCerta = date("d/m/y H:i:s");
        } else {
            $this->horaCerta = $horaCerta;
        }
    }

    /**
     * 
     * @param type $inicioHorarioVerao
     */
    public function setInicioHorarioVerao($inicioHorarioVerao) {
        $this->inicioHorarioVerao = $inicioHorarioVerao;
    }

    /**
     * 
     * @param type $fimHorarioVerao
     */
    public function setFimHorarioVerao($fimHorarioVerao) {
        $this->fimHorarioVerao = $fimHorarioVerao;
    }

    /**
     * 
     * @return string ex: 00+RH+000+20/10/11 15:10:26]00/00/00]00/00/00
     */
    public function getHoraRelogio() {
        $ret = $this->queryREP("00+RH+00");
        return substr($ret, 2, -2);
    }

    /**
     * metodo que ajusta a datahora do relogio ponto
     * @return string
     */
    public function ajustaHoraRelogio() {
        if (empty($this->inicioHorarioVerao)) {
            $this->setInicioHorarioVerao("00/00/00");
            $this->setFimHorarioVerao("00/00/00");
        }
        $ret = $this->queryREP("00+EH+00+{$this->horaCerta}]{$this->inicioHorarioVerao}]{$this->fimHorarioVerao}");
        return substr($ret, 2, -2);
    }

}
