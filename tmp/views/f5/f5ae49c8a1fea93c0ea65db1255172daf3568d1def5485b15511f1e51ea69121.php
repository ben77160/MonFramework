<?php

/* @blog/admin/posts/edit.twig */
class __TwigTemplate_aa6c3213be73c4842b1ada2e52b1b784bd7f1a91e1348ab6604cf9912490ca36 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@admin/layout.twig", "@blog/admin/posts/edit.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@admin/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Editer l'article";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "
  <h1>Editer l'article ";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "name", array()), "html", null, true);
        echo "</h1>

  <form action=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor((($context["routePrefix"] ?? null) . ".edit"), array("id" => twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "id", array()))), "html", null, true);
        echo "\" method=\"post\">
    ";
        // line 10
        $this->loadTemplate((($context["viewPath"] ?? null) . "/form.twig"), "@blog/admin/posts/edit.twig", 10)->display($context);
        // line 11
        echo "    <button class=\"btn btn-primary\" type=\"submit\">Editer</button>
  </form>

";
    }

    public function getTemplateName()
    {
        return "@blog/admin/posts/edit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 11,  50 => 10,  46 => 9,  41 => 7,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/admin/posts/edit.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\admin\\posts\\edit.twig");
    }
}
