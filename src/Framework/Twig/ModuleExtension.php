<?php


namespace App\Framework\Twig;

use Framework\App;

class ModuleExtension extends \Twig_Extension
{

    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('module_enabled', [$this, 'moduleEnabled'])
        ];
    }

    public function moduleEnabled(string $moduleName): bool
    {
        foreach ($this->app->getModules() as $module) {
            if ($module::NAME === $moduleName) {
                return true;
            }
        }
        return false;
    }
}
