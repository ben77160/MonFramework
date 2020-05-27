<?php

/* @blog/admin/menu.twig */
class __TwigTemplate_6d45989a9927a28f458e176de4f07b9b03e4bc8d85c95b8709e678f0e4beff31 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<li class=\"nav-item\">
  <a class=\"nav-link ";
        // line 2
        echo (($this->env->getExtension('Framework\Router\RouterTwigExtension')->isSubPath("blog.admin.index")) ? ("active") : (""));
        echo "\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.admin.index"), "html", null, true);
        echo "\">Articles</a>
</li>
<li class=\"nav-item\">
  <a class=\"nav-link ";
        // line 5
        echo (($this->env->getExtension('Framework\Router\RouterTwigExtension')->isSubPath("blog.category.admin.index")) ? ("active") : (""));
        echo "\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.category.admin.index"), "html", null, true);
        echo "\">Catégories</a>
</li>";
    }

    public function getTemplateName()
    {
        return "@blog/admin/menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 5,  22 => 2,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/admin/menu.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\admin\\menu.twig");
    }
}
