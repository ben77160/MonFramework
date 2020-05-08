<?php

namespace Framework\Renderer;

class TwigRenderer implements RendererInterface
{
    private $twig;
    private $loader;

   public function __construct(\Twig_Loader_Filesystem $loader, \Twig_Environment $twig)
   {
       //on initialise
       $this->loader = $loader;
       $this->twig = $twig;
   }

    /**
     * Permet de rajouter un chemin pour charger les vues
     * @inheritDoc
     * @throws \Twig_Error_Loader
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * Permet de rendre une vue
     * Le chemin peut être précisé avec des namespace rajouté via addPath()
     * @inheritDoc
     */
    public function render(string $view, array $params = []): string
    {
      return $this->twig->render($view . '.twig', $params);
    }

    /**
     * @inheritDoc
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}