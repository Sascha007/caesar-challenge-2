<?php

namespace Tests\Unit;

use App\Services\CaesarService;
use PHPUnit\Framework\TestCase;

class CaesarServiceTest extends TestCase
{
    private CaesarService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CaesarService();
    }

    public function test_encrypt_with_shift_3(): void
    {
        $plaintext = 'HELLO';
        $encrypted = $this->service->encrypt($plaintext, 3);
        $this->assertEquals('KHOOR', $encrypted);
    }

    public function test_encrypt_preserves_case(): void
    {
        $plaintext = 'Hello World';
        $encrypted = $this->service->encrypt($plaintext, 1);
        $this->assertEquals('Ifmmp Xpsme', $encrypted);
    }

    public function test_encrypt_preserves_non_alpha_characters(): void
    {
        $plaintext = 'Hello, World!';
        $encrypted = $this->service->encrypt($plaintext, 1);
        $this->assertEquals('Ifmmp, Xpsme!', $encrypted);
    }

    public function test_decrypt_reverses_encryption(): void
    {
        $plaintext = 'The quick brown fox';
        $shift = 13;
        
        $encrypted = $this->service->encrypt($plaintext, $shift);
        $decrypted = $this->service->decrypt($encrypted, $shift);
        
        $this->assertEquals($plaintext, $decrypted);
    }

    public function test_encrypt_with_shift_0(): void
    {
        $plaintext = 'HELLO';
        $encrypted = $this->service->encrypt($plaintext, 0);
        $this->assertEquals('HELLO', $encrypted);
    }

    public function test_encrypt_with_shift_26(): void
    {
        $plaintext = 'HELLO';
        $encrypted = $this->service->encrypt($plaintext, 26);
        $this->assertEquals('HELLO', $encrypted); // 26 mod 26 = 0
    }

    public function test_brute_force_returns_all_possibilities(): void
    {
        $encrypted = 'KHOOR';
        $results = $this->service->bruteForce($encrypted);
        
        $this->assertCount(26, $results);
        $this->assertContains('HELLO', $results); // Shift 3 decryption
    }
}
