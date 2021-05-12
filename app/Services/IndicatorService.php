<?php

namespace App\Services;

class IndicatorService
{
    /**
     * @param string $type
     * @param int $length
     * @return string|bool
     */
    public function randomize(string $type, int $length)
    {
        if (!in_array($type, [
            'string',
            'number',
            'guid',
            'alphanumeric'
        ])) {
            return false;
        }

        return $this->$type($length);
    }

    public function string(int $length = 8): string
    {
        return $this->randomizeString($this->getLetters(), $length);
    }

    public function number(int $length = 8): string
    {
        return $this->randomizeString($this->getDigits(), $length);
    }

    public function guid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function alphanumeric(int $length = 8): string
    {
        $result = '';

        // In case we don't get both letters and digits
        while (preg_match('/\d+[A-Za-z0-9]*/', $result) === 0) {
            $result = $this->randomizeString($this->getLetters() . $this->getDigits(), $length);
        }

        return $result;
    }

    private function randomizeString(string $string, int $length): string
    {
        $result = '';

        while (($len = strlen($result)) < $length) {
            $size = $length - $len;
            $result .= substr(str_shuffle($string), 0, $size);
        }

        return str_shuffle($result);
    }

    private function getLetters(): string
    {
        return 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }

    private function getDigits(): string
    {
        return '1234567890';
    }
}