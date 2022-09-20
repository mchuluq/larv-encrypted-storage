<?php


namespace Mchuluq\Larv\EncryptedStorage\CipherMethods;

use Closure;
use Mchuluq\Larv\EncryptedStorage\Exceptions\InvalidCipherMethod;
use Mchuluq\Larv\EncryptedStorage\Exceptions\UnregisteredCipherMethod;
use Mchuluq\Larv\EncryptedStorage\Interfaces\CipherMethodInterface;

/**
 * Class CipherMethodFactory
 * @package Mchuluq\Larv\EncryptedStorage\CipherMethods
 */
class CipherMethodFactory
{
    /** @var array Custom CipherMethod resolvers */
    protected static $resolvers = [];

    /**
     * Returns an implementation of CipherMethodInterface registered under the provided alias.
     *
     * @param array $config
     * @return CipherMethodInterface
     * @throws InvalidCipherMethod
     * @throws UnregisteredCipherMethod
     */
    public static function make(array $config): CipherMethodInterface
    {
        if (isset(static::$resolvers[$config['cipher-method']])) {
            $cipherMethod = call_user_func(static::$resolvers[$config['cipher-method']], $config);
            if ($cipherMethod instanceof CipherMethodInterface) {
                return $cipherMethod;
            }

            throw new InvalidCipherMethod($config['cipher-method']);
        }

        switch ($config['cipher-method']) {
            case 'aes-256-cbc':
                return new OpenSslCipherMethod($config['key'], $config['cipher-method']);
            default:
                throw new UnregisteredCipherMethod($config['cipher-method']);
        }
    }

    /**
     * Registers custom implementations of CipherMethodInterface.
     *
     * @param string $cipherMethod
     * @param Closure $resolver
     */
    public static function registerResolver(string $cipherMethod, Closure $resolver)
    {
        self::$resolvers[$cipherMethod] = $resolver;
    }
}