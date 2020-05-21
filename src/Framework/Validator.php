<?php

namespace Framework;

use Framework\Database\Table;
use Framework\Validator\ValidationError;

class Validator
{

    /**
     * @var array
     */
    private $params;

    /**
     * @var string[]
     */
    private $errors = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Vérifie que les champs sont présents dans le tableau
     *
     * @param string[] ...$keys
     * @return Validator
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    /**
     * Vérifie que le champs n'est pas vide
     *
     * @param string[] ...$keys
     * @return Validator
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);
        if (!is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (!is_null($min) &&
            $length < $min
        ) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }
        if (!is_null($max) &&
            $length > $max
        ) {
            $this->addError($key, 'maxLength', [$max]);
        }
        return $this;
    }

    /**
     * Vérifie que l'élément est un slug
     *
     * @param string $key
     * @return Validator
     */
    public function slug(string $key): self
    {
        $value = $this->getValue($key);
        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }
        return $this;
    }

    /**
     * Vérifie qu'une date correspond au format demandé
     *
     * @param string $key
     * @param string $format
     * @return Validator
     */
    public function dateTime(string $key, string $format = "Y-m-d H:i:s"): self
    {
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    /**
     * Vérifie que la clef existe dans la table donnée
     *
     * @param string $key
     * @param string $table
     * @param \PDO $pdo
     * @return Validator
     */
    public function exists(string $key, string $table, \PDO $pdo): self
    {
        $value = $this->getValue($key);
        $statement = $pdo->prepare("SELECT id FROM $table WHERE id = ?");
        $statement->execute([$value]);
        if ($statement->fetchColumn() === false) {
            $this->addError($key, 'exists', [$table]);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Récupère les erreurs
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Ajoute une erreur
     *
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    private function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}
