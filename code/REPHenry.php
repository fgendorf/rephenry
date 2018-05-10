<?php

/**
 * Classe de comunicação com relogios ponto HENRY versão 8x
 */


class REPHenry {

    protected $almostAscii = ' !"#$%&' . "'" . '()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[' . '\\' . ']^_`abcdefghijklmnopqrstuvwxyz{|}';
    protected $hex = "0123456789ABCDEF";
    protected $factory;
    protected $socket;
    protected $server;
    protected $port;

    public function __construct() {
        $this->factory = new \Socket\Raw\Factory();
    }

    public function setServer($server) {
        $this->server = $server;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function connect() {
        $this->socket = $this->factory->createClient("{$this->server}:{$this->port}");
    }

    function generateParamLength($data) {
        $paramLength = strlen($data);
        $h1 = $paramLength % 256;
        $h16 = floor($paramLength / 256);
        $h1Str = chr(($h1)) . chr(($h16));
        return $h1Str;
    }

    public function checkSum($data) {
        $i;
        $check = 0;
        $paramLength = strlen($data);
        for ($i = 0; $i < $paramLength; $i++) {
            $textPos = substr($data, $i, 1);
            $val = ord($textPos);
            $check ^= $val;
        }
        $check ^= ( $paramLength % 256);
        $check ^= ( $paramLength / 256);
        $h16 = floor($check / 16);
        $h1 = $check % 16;
        return chr(hexdec(substr($this->hex, $h16, 1) . substr($this->hex, $h1, 1)));
    }

    public function textFormat($data) {
        $BYTE_INIT = chr(2);
        $BYTE_END = chr(3);

        $str = $BYTE_INIT;
        $str .= $this->generateParamLength($data);
        $str .= $data;
        $str .= $this->checkSum($data);
        $str .= $BYTE_END;

        return $str;
    }

    public function strToHex($string) {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2) . ' ';
        }
        return strToUpper($hex);
    }

    /**
     * Metodo que envia dados para o relogio e devolve a resposta que o relógio retornou
     * @param string $cmd
     * @return string
     */
    public function queryREP($cmd) {
        $this->socket->write($this->textFormat($cmd));
        do {
            $d = $this->socket->read(128);
            $data .= $d;
        } while (substr($d,-1,1)!=chr(3));
        return $data;
    }

}
