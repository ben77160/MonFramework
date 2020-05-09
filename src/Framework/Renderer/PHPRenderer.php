<?php


namespace Framework\Renderer;

use Framework\Renderer\RendererInterface;

class PHPRenderer implements RendererInterface
{
    const DEFAULT_NAMESPACE = '__MAIN';

    private $paths = [];

    /**
     * Variables globalement accessibles pour toutes les vues
     * @var array
     */
    private $globals = [];

    public function __construct(?string $defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

    /**
     * Permet de rajouter un chemin pour charger de vues
     * @param string $namespace
     * @param string|null $path
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        //On definit un chemin
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    /**
     * Permet de rendre une vue
     * Le chemin peut-être précisé avec des namespace rajoutés via addPath()
     *  $this->render('@blog/view');
     *  $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        //On verifie si on a un namespace dans le chemin de la vue
        if ($this->hasNamespace($view)) {
            //on passe la vue
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            //On retourne une vue
            $path = $this->paths[self::DEFAULT_NAMESPACE]. DIRECTORY_SEPARATOR . $view . '.php';
        }
        ob_start();
        $renderer = $this;
        //On extrait les variables pour permettre à nos paramêtres d'être accessible à la vue
        extract($this->globals);
        extract($params);
        require($path);
        return ob_get_clean();
    }

    /**
     * Permet de rajouter des variables globales à toutes les vues
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    private function hasNamespace(string $view):bool
    {
        if ($view[0] === '@') {
            return true;
        }
        return false;
    }

    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') -1);
    }

    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }
}
