<?php

/* @blog/show.twig */
class __TwigTemplate_511d94d6c930619fca93f1441e87797ba5e14f9110e81244bda69e33d80ea90d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "@blog/show.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "name", array()), "html", null, true);
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "
<h1>";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "name", array()), "html", null, true);
        echo "</h1>

<p class=\"text-muted\">
  ";
        // line 10
        if (twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "category_id", array())) {
            // line 11
            echo "    <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.category", array("slug" => twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "category_slug", array()))), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "category_name", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "category_name", array()), "html", null, true);
            echo "</a>
  ";
        }
        // line 13
        echo "  ";
        echo $this->env->getExtension('Framework\Twig\TimeExtension')->ago(twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "created_at", array()));
        echo "
</p>

";
        // line 16
        echo nl2br(twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["post"] ?? null), "content", array()), "html", null, true));
        echo "

";
    }

    public function getTemplateName()
    {
        return "@blog/show.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 16,  59 => 13,  49 => 11,  47 => 10,  41 => 7,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/show.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\show.twig");
    }
}
