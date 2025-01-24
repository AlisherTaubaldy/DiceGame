<?php
namespace Game;

class FairRandom{
    private string $key;

    public function generate(int $min, int $max){
        $this->key = bin2hex(random_bytes(32));
        $value = random_int($min, $max);
        $hmac = hash_hmac('sha256', (string)$value, $this->key);

        return [$value, $hmac];
    }

    public function revealKey(): string {
        return $this->key;
    }
}