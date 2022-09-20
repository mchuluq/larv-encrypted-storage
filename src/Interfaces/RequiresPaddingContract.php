<?php


namespace Mchuluq\Larv\EncryptedStorage\Interfaces;


interface RequiresPaddingContract
{
    public function getPaddingSize(int $filesize): int;
}