<?php

/* @blog/admin/categories/index.twig */
class __TwigTemplate_a0b0f96b07d1d7d1c9931689235f3499f9b236ff58db44f8d5e3f9b238355122 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@admin/layout.twig", "@blog/admin/categories/index.twig", 1);
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
        echo "Editer les catégories";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "
  <p class=\"text-right\">
    <a href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor((($context["routePrefix"] ?? null) . ".create")), "html", null, true);
        echo "\" class=\"btn btn-primary\">
      Ajouter une catégorie
    </a>
  </p>

  <table class=\"table table-striped\">
    <thead>
    <tr>
      <td>Titre</td>
      <td>Actions</td>
    </tr>
    </thead>
    <tbody>
    ";
        // line 21
        $context["token_input"] = $this->env->getExtension('Framework\Twig\CsrfExtension')->csrfInput();
        // line 22
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 23
            echo "    <tr>
      <td>";
            // line 24
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["item"], "name", array()), "html", null, true);
            echo "</td>
      <td>
        <a href=\"";
            // line 26
            echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor((($context["routePrefix"] ?? null) . ".edit"), array("id" => twig_get_attribute($this->env, $this->getSourceContext(), $context["item"], "id", array()))), "html", null, true);
            echo "\" class=\"btn btn-primary\">Editer</a>
        <form style=\"display: inline;\" action=\"";
            // line 27
            echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor((($context["routePrefix"] ?? null) . ".delete"), array("id" => twig_get_attribute($this->env, $this->getSourceContext(), $context["item"], "id", array()))), "html", null, true);
            echo "\" method=\"POST\" onsubmit=\"return confirm('êtes vous sûr ?')\">
          <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
          <button class=\"btn btn-danger\">Supprimer</button>
          ";
            // line 30
            echo ($context["token_input"] ?? null);
            echo "
        </form>
      </td>
    </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "    </tbody>
  </table>

  ";
        // line 38
        echo $this->env->getExtension('Framework\Twig\PagerFantaExtension')->paginate(($context["items"] ?? null), (($context["routePrefix"] ?? null) . ".index"));
        echo "

";
    }

    public function getTemplateName()
    {
        return "@blog/admin/categories/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  99 => 38,  94 => 35,  83 => 30,  77 => 27,  73 => 26,  68 => 24,  65 => 23,  60 => 22,  58 => 21,  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/admin/categories/index.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\admin\\categories\\index.twig");
    }
}
