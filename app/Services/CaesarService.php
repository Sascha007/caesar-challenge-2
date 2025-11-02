<?php

namespace App\Services;

class CaesarService
{
    /**
     * Encrypt text using Caesar cipher with given shift
     */
    public function encrypt(string $text, int $shift): string
    {
        $shift = $shift % 26; // Normalize shift to 0-25
        $result = '';

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];

            if (ctype_upper($char)) {
                // Uppercase letters
                $result .= chr(((ord($char) - 65 + $shift) % 26) + 65);
            } elseif (ctype_lower($char)) {
                // Lowercase letters
                $result .= chr(((ord($char) - 97 + $shift) % 26) + 97);
            } else {
                // Non-alphabetic characters remain unchanged
                $result .= $char;
            }
        }

        return $result;
    }

    /**
     * Decrypt text using Caesar cipher with given shift
     */
    public function decrypt(string $text, int $shift): string
    {
        // Decrypting is the same as encrypting with negative shift
        return $this->encrypt($text, 26 - ($shift % 26));
    }

    /**
     * Try to decrypt with all possible shifts and return array of results
     */
    public function bruteForce(string $text): array
    {
        $results = [];
        for ($shift = 0; $shift < 26; $shift++) {
            $results[$shift] = $this->decrypt($text, $shift);
        }
        return $results;
    }
}
