<?php
namespace App\Framework\Session;

class ArraySession implements SessionInterface
{
    private $session = [];


    /**
     * Récupère une information en Session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        //ON Verifie si la clef existe dans la session
        if (array_key_exists($key, $_SESSION)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Ajoute une information en Session
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * Supprime une clef en session
     * @inheritDoc
     */
    public function delete(string $key):void
    {
        unset($this->session[$key]);
    }
}
