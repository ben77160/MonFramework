<?php

/* @blog/admin/widget.twig */
class __TwigTemplate_b22dab4dfcffea101655399725082188630755cfc6d5278c7991c55ac35c9793 extends Twig_Template
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
        echo "<div class=\"col-sm-4\">
  <div class=\"card\">
    <div class=\"card-block\">
      <h4 class=\"card-title\">";
        // line 4
        echo twig_escape_filter($this->env, ($context["count"] ?? null), "html", null, true);
        echo "</h4>
      <p class=\"card-text\">Article";
        // line 5
        if ((($context["count"] ?? null) > 1)) {
            echo "s";
        }
        echo "</p>
      <a href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.admin.index"), "html", null, true);
        echo "\" class=\"btn btn-primary\">Gérer les articles</a>
    </div>
  </div>
</div>";
    }

    public function getTemplateName()
    {
        return "@blog/admin/widget.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 6,  28 => 5,  24 => 4,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/admin/widget.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\admin\\widget.twig");
    }
}
