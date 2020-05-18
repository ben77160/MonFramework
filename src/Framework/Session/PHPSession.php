<?php
namespace App\Framework\Session;

class PHPSession implements SessionInterface
{
    /**
     * Assure que la session est démarrée
     */
    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Récupère une information en Session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        //On vérifie que la session est bien démarrée
        $this->ensureStarted();
        //ON Verifie si la clef existe dans la session
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
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
        //On vérifie que la session est bien démarrée
        $this->ensureStarted();

         $_SESSION[$key] = $value;
    }

    /**
     * Supprime une clef en session
     * @inheritDoc
     */
    public function delete(string $key):void
    {
        //On vérifie que la session est bien démarrée
        $this->ensureStarted();

        unset($_SESSION[$key]);
    }
}
