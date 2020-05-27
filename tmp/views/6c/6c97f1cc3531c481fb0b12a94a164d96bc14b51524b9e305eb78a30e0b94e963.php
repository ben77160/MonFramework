<?php

/* @blog/admin/posts/form.twig */
class __TwigTemplate_e58009bab5424c6aba4b3dbd207b94a72679244dbc6f7eaf8273d9e2ab0f88b1 extends Twig_Template
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
        echo $this->env->getExtension('Framework\Twig\CsrfExtension')->csrfInput();
        echo "
<div class=\"row\">
  <div class=\"col-md-4\">
    ";
        // line 4
        echo $this->env->getExtension('Framework\Twig\FormExtension')->field($context, "name", twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "name", array()), "Titre de l'article");
        echo "
  </div>
  <div class=\"col-md-4\">
    ";
        // line 7
        echo $this->env->getExtension('Framework\Twig\FormExtension')->field($context, "category_id", twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "category_id", array()), "Catégorie", array("options" => ($context["categories"] ?? null)));
        echo "
  </div>
  <div class=\"col-md-4\">
    ";
        // line 10
        echo $this->env->getExtension('Framework\Twig\FormExtension')->field($context, "slug", twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "slug", array()), "Lien");
        echo "
  </div>
</div>
";
        // line 13
        echo $this->env->getExtension('Framework\Twig\FormExtension')->field($context, "content", twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "content", array()), "content", array("type" => "textarea"));
        echo "
";
        // line 14
        echo $this->env->getExtension('Framework\Twig\FormExtension')->field($context, "created_at", twig_get_attribute($this->env, $this->getSourceContext(), ($context["item"] ?? null), "created_at", array()), "Date de création", array("class" => "datepicker"));
        echo "
";
    }

    public function getTemplateName()
    {
        return "@blog/admin/posts/form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 14,  43 => 13,  37 => 10,  31 => 7,  25 => 4,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/admin/posts/form.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\admin\\posts\\form.twig");
    }
}
