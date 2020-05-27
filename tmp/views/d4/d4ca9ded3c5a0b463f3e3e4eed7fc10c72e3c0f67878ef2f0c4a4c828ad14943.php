<?php

/* @admin/dashboard.twig */
class __TwigTemplate_21c58b968c8b3200c2c6b4b4d05b1513e701b285a210527308075b62c1fa5666 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@admin/layout.twig", "@admin/dashboard.twig", 1);
        $this->blocks = array(
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
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "
  <h1>Dashboard</h1>

  <div class=\"row\">
    ";
        // line 8
        echo ($context["widgets"] ?? null);
        echo "
  </div>
";
    }

    public function getTemplateName()
    {
        return "@admin/dashboard.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 8,  31 => 4,  28 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@admin/dashboard.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Admin\\views\\dashboard.twig");
    }
}
