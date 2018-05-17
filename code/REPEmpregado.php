<?php

/**
 * Classe que extende a REPHenry para manipulação de empregados
 */
class REPEmpregado extends REPHenry {

    /**
     * Numero do PIS do empregado
     * @var int 
     */
    protected $pisEmpregado;

    /**
     * Nome ou login do empregado
     * @var string 
     */
    protected $nomeEmpregado;

    /**
     * matricula do empregado
     * @var int 
     */
    protected $matriculaEmpregado;

    /**
     * ativa biometria 1 = sim 0 = não
     * @var int 
     */
    protected $biometriaEmpregado = 1;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Setter do numero do PIS
     * @param int $pisEmpregado
     */
    public function setPisEmpregado($pisEmpregado) {
        $this->pisEmpregado = $pisEmpregado;
    }

    /**
     * setter do nome ou login do empregado
     * @param string $nomeEmpregado
     */
    public function setNomeEmpregado($nomeEmpregado) {
        $this->nomeEmpregado = $nomeEmpregado;
    }

    /**
     * setter da matricula do empregado
     * @param int $matriculaEmpregado
     */
    public function setMatriculaEmpregado($matriculaEmpregado) {
        $this->matriculaEmpregado = $matriculaEmpregado;
    }

    /**
     * metodo de cadastro de empregado, com os valores de PIS,NOME e Matricula
     * definidos pelos metodos
     * setPisEmpregado()
     * setNomeEmpregado()
     * setMatriculaEmpregado()
     * @return string
     */
    public function cadastraEmpregado() {
        $ret = $this->queryREP("00+EU+00+1+I[{$this->pisEmpregado}[{$this->nomeEmpregado}[{$this->biometriaEmpregado}[1[{$this->matriculaEmpregado}");
        return str_replace(chr(2), '', str_replace(chr(3), '', $ret));
    }

   /**
     * Metodo que retorna os empregados cadastrados no relogio
     * @return array
     */
    public function listaEmpregados() {
        $qnt = 0;
        $listusers = array();
        do {
            $CMD = "00+RU+00+1]{$qnt}";
            $ret = $this->queryREP($CMD);
            //print_r($ret);
            $chkcalc = ord($this->checkSum(substr($ret, 3, -2)));
            $chkrcv = ord(substr($ret, -2, 1));
            if ($chkcalc == $chkrcv) {
                $ret = substr($ret, 2, -2);
                //echo ' | ' . $chkrcv . ' - (' . substr($ret, -2, 1) . ')' . PHP_EOL;
                $proc = preg_split('/]/', $ret);
                $cmds = preg_split('/\+/', $proc[0]);
                $proc[0] = $cmds[4];
            }
            if ($cmds[3] > 0) {
                $listusers = array_merge($listusers, $proc);
            }
            $qnt += 1;
        } while ($cmds[3] >= 0);
        $y = -1;
        for ($x = 0; sizeof($listusers) > $x; ++$x) {
            ++$y;
            $regarray[$y] = array_combine(array('pis', 'nome', 'biometria', 'nmatriculas', 'matriculas'), preg_split('/\[/', $listusers[$x]));
        }
        //print_r($regarray);
        return $regarray;
    }

    /**
     * Metodo para deletar empregado
     * * definidos pelos metodos
     * setPisEmpregado()
     * setNomeEmpregado()
     * setMatriculaEmpregado()
     * @return string
     */
    public function deleteEmpregado() {
        $ret = $this->queryREP("00+EU+00+1+E[{$this->pisEmpregado}[{$this->nomeEmpregado}[{$this->biometriaEmpregado}[1[{$this->matriculaEmpregado}");
        return substr($ret,2,-2);
    }

}
