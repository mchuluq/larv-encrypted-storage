<?php

namespace Mchuluq\Larv\EncryptedStorage\Interfaces;

interface CipherMethodInterface
{
    public function encrypt(string $plaintext, bool $eof = false): string;

    public function decrypt(string $ciphertext, bool $eof = false): string;

    public function getBlockSize(): int;

    public function reset(): void;

    public function seek(int $offset, string $whence = SEEK_SET);
}
