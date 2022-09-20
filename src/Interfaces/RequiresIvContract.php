<?php


namespace Mchuluq\Larv\EncryptedStorage\Interfaces;


interface RequiresIvContract
{
    public function setIv(string $iv);

    public function getIv(): string;

    public function generateIv(): string;

    public function getIvSize(): int;

}