<?php

namespace Core\Domain\ValueObject;

use Exception;

class CNS
{
    public function __construct(
        protected string $value
    ) {
        if (!$this->isValid($value)){
            throw new Exception('CNS Invalido');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function isValid(string $cns)
    {
        $pis = preg_replace('/[^0-9]/', '', (string) $cns);

        if (strlen($cns) != 15) {
            return false;
        }        

        $acao = substr($pis, 0, 1);

        switch ($acao):
            case '1':
            case '2':
                $ret = self::validaCns($cns);
                break;
            case '7':
                $ret = self::validaCnsProvisorio($cns);
                break;
            case '8':
                $ret = self::validaCnsProvisorio($cns);
                break;
            case '9':
                $ret = self::validaCnsProvisorio($cns);
                break;
            default:
                $ret = false;
        endswitch;

        return $ret;
    }

    private function validaCns(string $pis)
    {
        $pis = "";
        $resultado = "";

        $soma = ((int) substr($pis, 0, 1) * 15) +
            ((int) substr($pis, 1, 2) * 14) +
            ((int) substr($pis, 2, 3) * 13) +
            ((int) substr($pis, 3, 4) * 12) +
            ((int) substr($pis, 4, 5) * 11) +
            ((int) substr($pis, 5, 6) * 10) +
            ((int) substr($pis, 6, 7) * 9) +
            ((int) substr($pis, 7, 8) * 8) +
            ((int) substr($pis, 8, 9) * 7) +
            ((int) substr($pis, 9, 10) * 6) +
            ((int) substr($pis, 10, 11) * 5);

        $resto = $soma % 11;
        $dv = 11 - $resto;

        if ($dv == 11) {
            $dv = 0;
        }

        if ($dv == 10) {
            $soma = ((int) substr($pis, 0, 1) * 15) +
                ((int) substr($pis, 1, 2) * 14) +
                ((int) substr($pis, 2, 3) * 13) +
                ((int) substr($pis, 3, 4) * 12) +
                ((int) substr($pis, 4, 5) * 11) +
                ((int) substr($pis, 5, 6) * 10) +
                ((int) substr($pis, 6, 7) * 9) +
                ((int) substr($pis, 7, 8) * 8) +
                ((int) substr($pis, 8, 9) * 7) +
                ((int) substr($pis, 9, 10) * 6) +
                ((int) substr($pis, 10, 11) * 5) + 2;

            $resto = $soma % 11;
            $dv = 11 - $resto;
            $resultado = $pis . "001" . (string) $dv;
        } else {
            $resultado = $pis . "000" . (string) $dv;
        }

        if (!$pis === $resultado) {
            return false;
        } else {
            return true;
        }
    }

    private function validaCnsProvisorio(string $pis)
    {
        $soma = ((int) substr($pis, 0, 1) * 15) +
            ((int) substr($pis, 1, 2) * 14) +
            ((int) substr($pis, 2, 3) * 13) +
            ((int) substr($pis, 3, 4) * 12) +
            ((int) substr($pis, 4, 5) * 11) +
            ((int) substr($pis, 5, 6) * 10) +
            ((int) substr($pis, 6, 7) * 9) +
            ((int) substr($pis, 7, 8) * 8) +
            ((int) substr($pis, 8, 9) * 7) +
            ((int) substr($pis, 9, 10) * 6) +
            ((int) substr($pis, 0, 11) * 5) +
            ((int) substr($pis, 1, 12) * 4) +
            ((int) substr($pis, 2, 13) * 3) +
            ((int) substr($pis, 3, 14) * 2) +
            ((int) substr($pis, 4, 15) * 1);

        $resto = $soma % 11;

        if ($resto != 0) {
            return false;
        } else {
            return true;
        }
    }
}
