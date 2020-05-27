<?php

/* @blog/index.twig */
class __TwigTemplate_6f9314725e78ecab93a88eb20c59bc9b8acdd05021db54c224b3de39ec9b2c2e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "@blog/index.twig", 1);
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
        // line 4
        echo "  ";
        if (($context["category"] ?? null)) {
            // line 5
            echo "    Catégorie : ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["category"] ?? null), "name", array()), "html", null, true);
            if ((($context["page"] ?? null) > 1)) {
                echo ", page ";
                echo twig_escape_filter($this->env, ($context["page"] ?? null), "html", null, true);
            }
            // line 6
            echo "  ";
        } else {
            // line 7
            echo "    Blog";
            if ((($context["page"] ?? null) > 1)) {
                echo ", page ";
                echo twig_escape_filter($this->env, ($context["page"] ?? null), "html", null, true);
            }
            // line 8
            echo "  ";
        }
    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        // line 12
        echo "
  ";
        // line 13
        if (($context["category"] ?? null)) {
            // line 14
            echo "    <h1>Catégorie : ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["category"] ?? null), "name", array()), "html", null, true);
            if ((($context["page"] ?? null) > 1)) {
                echo ", page ";
                echo twig_escape_filter($this->env, ($context["page"] ?? null), "html", null, true);
            }
            echo "</h1>
  ";
        } else {
            // line 16
            echo "    <h1>Bienvenue sur le blog";
            if ((($context["page"] ?? null) > 1)) {
                echo ", page ";
                echo twig_escape_filter($this->env, ($context["page"] ?? null), "html", null, true);
            }
            echo "</h1>
  ";
        }
        // line 18
        echo "  <div class=\"row\">
    <div class=\"col-md-9\">

      <div class=\"row\">

        ";
        // line 23
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_array_batch(($context["posts"] ?? null), 4));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 24
            echo "          <div class=\"card-deck\">
            ";
            // line 25
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["row"]);
            foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
                // line 26
                echo "              <div class=\"card\">
                ";
                // line 27
                if (twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "category_name", array())) {
                    // line 28
                    echo "                  <div class=\"card-header\">";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "category_name", array()), "html", null, true);
                    echo "</div>
                ";
                }
                // line 30
                echo "                <div class=\"card-block\">
                  <h4 class=\"card-title\">
                    <a href=\"";
                // line 32
                echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.show", array("slug" => twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "slug", array()), "id" => twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "id", array()))), "html", null, true);
                echo "\">
                      ";
                // line 33
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "name", array()), "html", null, true);
                echo "
                    </a>
                  </h4>
                  <p class=\"card-text\">
                    ";
                // line 37
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('Framework\Twig\TextExtension')->excerpt(twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "content", array())), "html", null, true));
                echo "
                  </p>
                  <p class=\"text-muted\">";
                // line 39
                echo $this->env->getExtension('Framework\Twig\TimeExtension')->ago(twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "created_at", array()));
                echo "</p>
                </div>
                <div class=\"card-footer\">
                  <a href=\"";
                // line 42
                echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.show", array("slug" => twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "slug", array()), "id" => twig_get_attribute($this->env, $this->getSourceContext(), $context["post"], "id", array()))), "html", null, true);
                echo "\" class=\"btn btn-primary\">
                    Voir l'article
                  </a>
                </div>
              </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 48
            echo "          </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        echo "      </div>

      ";
        // line 52
        if (($context["category"] ?? null)) {
            // line 53
            echo "        ";
            echo $this->env->getExtension('Framework\Twig\PagerFantaExtension')->paginate(($context["posts"] ?? null), "blog.category", array("slug" => twig_get_attribute($this->env, $this->getSourceContext(), ($context["category"] ?? null), "slug", array())));
            echo "
      ";
        } else {
            // line 55
            echo "        ";
            echo $this->env->getExtension('Framework\Twig\PagerFantaExtension')->paginate(($context["posts"] ?? null), "blog.index");
            echo "
      ";
        }
        // line 57
        echo "    </div>
    <div class=\"col-md-3\">
      <ul class=\"list-group\">
        ";
        // line 60
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["categories"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["c"]) {
            // line 61
            echo "          <li class=\"list-group-item ";
            if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "id", array()) == twig_get_attribute($this->env, $this->getSourceContext(), ($context["category"] ?? null), "id", array()))) {
                echo "active";
            }
            echo "\">
            <a style=\"color:inherit;\" href=\"";
            // line 62
            echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.category", array("slug" => twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "slug", array()))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "name", array()), "html", null, true);
            echo "</a>
          </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['c'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 65
        echo "      </ul>
    </div>
  </div>

";
    }

    public function getTemplateName()
    {
        return "@blog/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  202 => 65,  191 => 62,  184 => 61,  180 => 60,  175 => 57,  169 => 55,  163 => 53,  161 => 52,  157 => 50,  150 => 48,  138 => 42,  132 => 39,  127 => 37,  120 => 33,  116 => 32,  112 => 30,  106 => 28,  104 => 27,  101 => 26,  97 => 25,  94 => 24,  90 => 23,  83 => 18,  74 => 16,  64 => 14,  62 => 13,  59 => 12,  56 => 11,  51 => 8,  45 => 7,  42 => 6,  35 => 5,  32 => 4,  29 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@blog/index.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Blog\\views\\index.twig");
    }
}
