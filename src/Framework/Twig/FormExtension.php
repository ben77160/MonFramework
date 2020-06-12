<?php

namespace Framework\Twig;

class FormExtension extends \Twig_Extension
{

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('field', [$this, 'field'], [
                'is_safe' => ['html'],
                'needs_context' => true
            ])
        ];
    }

    /**
     * Génère le code HTML d'un champs
     * @param array $context Contexte de la vue Twig
     * @param string $key Clef du champs
     * @param mixed $value Valeur du champs
     * @param string|null $label Label à utiliser
     * @param array $options
     * @param array $attributes
     * @return string
     */
    public function field(
        array $context,
        string $key,
        $value,
        ?string $label = null,
        array $options = [],
        array $attributes = []
    ): string {
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = array_merge([
            'class' => trim('form-control ' . ($options['class'] ?? '')),
            'name'  => $key,
            'id'    => $key
        ], $attributes);

        if ($error) {
            $class .= ' has-danger';
            $attributes['class'] .= ' form-control-danger';
        }
        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } elseif ($type === 'file') {
            $input = $this->file($attributes);
        } elseif ($type === 'checkbox') {
            $input = $this->checkbox($value, $attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $attributes['type'] = $options['type'] ?? 'text';
            $input = $this->input($value, $attributes);
        }
        return "<div class=\"" . $class . "\">
              <label for=\"name\">{$label}</label>
              {$input}
              {$error}
            </div>";
    }

    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }

    /**
     * Génère l'HTML en fonction des erreurs du contexte
     * @param $context
     * @param $key
     * @return string
     */
    private function getErrorHtml($context, $key)
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class=\"form-text text-muted\">{$error}</small>";
        }
        return "";
    }

    /**
     * Génère un <input>
     * @param null|string $value
     * @param array $attributes
     * @return string
     */
    private function input(?string $value, array $attributes): string
    {
        return "<input " . $this->getHtmlFromArray($attributes) . " value=\"{$value}\">";
    }

    /**
     * Génère un <input type="checkbox">
     * @param null|string $value
     * @param array $attributes
     * @return string
     */
    private function checkbox(?string $value, array $attributes): string
    {
        $html = '<input type="hidden" name="' . $attributes['name'] . '" value="0"/>';
        if ($value) {
            $attributes['checked'] = true;
        }
        return $html . "<input type=\"checkbox\" " . $this->getHtmlFromArray($attributes) . " value=\"1\">";
    }

    private function file($attributes)
    {
        return "<input type=\"file\" " . $this->getHtmlFromArray($attributes) . ">";
    }

    /**
     * Génère un <textarea>
     * @param null|string $value
     * @param array $attributes
     * @return string
     */
    private function textarea(?string $value, array $attributes): string
    {
        return "<textarea " . $this->getHtmlFromArray($attributes) . ">{$value}</textarea>";
    }

    /**
     * Génère un <select>
     * @param null|string $value
     * @param array $options
     * @param array $attributes
     * @return string
     */
    private function select(?string $value, array $options, array $attributes)
    {
        $htmlOptions = array_reduce(array_keys($options), function (string $html, string $key) use ($options, $value) {
            $params = ['value' => $key, 'selected' => $key === $value];
            return $html . '<option ' . $this->getHtmlFromArray($params) . '>' . $options[$key] . '</option>';
        }, "");
        return "<select " . $this->getHtmlFromArray($attributes) . ">$htmlOptions</select>";
    }

    /**
     * Transforme un tableau $clef => $valeur en attribut HTML
     * @param array $attributes
     * @return string
     */
    private function getHtmlFromArray(array $attributes)
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string)$key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }
}
