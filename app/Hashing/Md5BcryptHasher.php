<?php

namespace App\Hashing;

use Illuminate\Hashing\BcryptHasher;

class Md5BcryptHasher extends BcryptHasher
{
    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array  $options
     * @return bool
     */
    public function check(#[\SensitiveParameter] $value, $hashedValue, array $options = [])
    {
        if (is_null($hashedValue) || (string) $hashedValue === '') {
            return false;
        }

        $hash = strtolower(trim((string) $hashedValue));

        // Check if the hash is 32-character hexadecimal (MD5)
        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            // Check JCow legacy salted MD5: md5($password . 'jcow')
            if (hash_equals($hash, md5($value . 'jcow'))) {
                return true;
            }

            // Check standard plain MD5
            if (hash_equals($hash, md5($value))) {
                return true;
            }

            // Check double MD5 fallback (used in some legacy CMS/forum integrations)
            if (hash_equals($hash, md5(md5($value)))) {
                return true;
            }

            return false;
        }

        // Use parent for bcrypt
        $this->verifyAlgorithm = false;
        return parent::check($value, $hashedValue, $options);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string  $hashedValue
     * @param  array  $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        $hash = strtolower(trim((string) $hashedValue));

        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            return true;
        }

        return parent::needsRehash($hashedValue, $options);
    }
}
