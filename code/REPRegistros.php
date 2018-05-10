<?php

/**
 * Classe que extende a REPHenry para recuperação de dados de Registros gerais do relogio
 */
class REPRegistros extends REPHenry {

    protected $idSequencial;

    public function __construct() {
        parent::__construct();
    }

    public function setIdSequencial($idSequencial) {
        $this->idSequencial = $idSequencial;
    }

    /**
     * método que coleta todos os registros do relógio a partir de um ID sequencial 
     * inicial, definido pelo metodo setIdSequencial.
     * caso contrario ele pega todos os registros armazenados no relogio
     * 
     * @return array
     */
    public function coletaRegistrosPonto() {
        $idinit = $this->idSequencial;
        do {
            $ret = $this->queryREP("00+RR+00+N]10]{$idinit}");
            $proc = preg_split('/]/', trim(str_replace(chr(3), '', str_replace(chr(2), '', $ret))));
            $chkcalc = ord($this->checkSum(substr($ret, 3, -2)));
            $chkrcv = ord(substr($ret, -2, 1));
            if ($chkcalc == $chkrcv) {
                $resp .= substr($proc[1], 0, -1);
            }
            $idinit += 10;
        } while ($proc[0] != "00+RR+113:");
        $registros = (explode("\n", $resp));
        array_pop($registros);
        return $registros;
    }

    /**
     * metodo que retorna apenas os registros de alteração de empresa no relógio ponto
     * 
     * @param array $registros
     * @return array
     */
    public function getInclusaoAlteracaoEmpresa($registros) {
        $y = 0;
        for ($x = 0; sizeof($registros) > $x; ++$x) {
            if (substr($registros[$x], 9, 1) == 2) {
                $regarray[$y]["NSR"] = substr($registros[$x], 0, 9);
                $regarray[$y]["tipo"] = substr($registros[$x], 9, 1);
                $regarray[$y]["data"] = substr($registros[$x], 10, 8);
                $regarray[$y]["horario"] = substr($registros[$x], 18, 4);
                $regarray[$y]["tipoempregador"] = substr($registros[$x], 22, 1);
                $regarray[$y]["numerodoc"] = substr($registros[$x], 23, 14);
                $regarray[$y]["cei"] = substr($registros[$x], 37, 12);
                $regarray[$y]["razaosocial"] = substr($registros[$x], 49, 150);
                $regarray[$y]["local"] = substr($registros[$x], 199, 100);
                ++$y;
            }
        }
        return $regarray;
    }

    /**
     * metodo que retorna apenas os registros de ponto por PIS
     * 
     * @param array $registros
     * @return array
     */
    public function getMarcacaoPonto($registros) {
        $y = 0;
        for ($x = 0; sizeof($registros) > $x; ++$x) {
            if (substr($registros[$x], 9, 1) == 3) {
                $regarray[$y]["NSR"] = substr($registros[$x], 0, 9);
                $regarray[$y]["tipo"] = substr($registros[$x], 9, 1);
                $date = DateTime::createFromFormat("dmYHi", substr($registros[$x], 10, 8) . substr($registros[$x], 18, 4));
                $regarray[$y]["datahorario"] = $date->format("Y-m-d H:i:s");
                $regarray[$y]["pis"] = substr($registros[$x], 22, 12);
                ++$y;
            }
        }
        return $regarray;
    }

    /**
     * metodo que retorna apenas os registros de ponto por matricula
     * 
     * @param array $registros
     * @return array
     */
    public function getMarcacaoPontoMatricula($registros) {
        $y = 0;
        for ($x = 0; sizeof($registros) > $x; ++$x) {
            if (substr($registros[$x], 9, 1) == 7) {
                $regarray[$y]["NSR"] = substr($registros[$x], 0, 9);
                $regarray[$y]["tipo"] = substr($registros[$x], 9, 1);
                $date = DateTime::createFromFormat("dmYHi", substr($registros[$x], 10, 8) . substr($registros[$x], 18, 4));
                $regarray[$y]["datahorario"] = $date->format("Y-m-d H:i:s");
                $regarray[$y]["matricula"] = substr($registros[$x], -6);
                ++$y;
            }
        }
        return $regarray;
    }

    /**
     * metodo que retorna apenas os registros de ajuste de relogio
     * 
     * @param array $registros
     * @return array
     */
    public function getAjusteRelogio($registros) {
        $y = 0;
        for ($x = 0; sizeof($registros) > $x; ++$x) {
            if (substr($registros[$x], 9, 1) == 4) {
                $regarray[$y]["NSR"] = substr($registros[$x], 0, 9);
                $regarray[$y]["tipo"] = substr($registros[$x], 9, 1);
                $regarray[$y]["dataantes"] = substr($registros[$x], 10, 8);
                $regarray[$y]["horarioantes"] = substr($registros[$x], 18, 4);
                $regarray[$y]["dataajustada"] = substr($registros[$x], 22, 8);
                $regarray[$y]["horaajustada"] = substr($registros[$x], 30, 4);
                ++$y;
            }
        }
        return $regarray;
    }

    /**
     * metodo que retorna apenas os registros de alteração de dados de empregados
     * cadastrados
     * 
     * @param array $registros
     * @return array
     */
    public function getInclusaoAlteracaoEmpregado($registros) {
        $y = 0;
        for ($x = 0; sizeof($registros) > $x; ++$x) {
            if (substr($registros[$x], 9, 1) == 5) {
                $regarray[$y]["NSR"] = substr($registros[$x], 0, 9);
                $regarray[$y]["tipo"] = substr($registros[$x], 9, 1);
                $regarray[$y]["data"] = substr($registros[$x], 10, 12);
                $regarray[$y]["acao"] = substr($registros[$x], 22, 1);
                $regarray[$y]["pis"] = substr($registros[$x], 23, 12);
                $regarray[$y]["colaborador"] = substr($registros[$x], 35, 52);
                ++$y;
            }
        }
        return $regarray;
    }

}
