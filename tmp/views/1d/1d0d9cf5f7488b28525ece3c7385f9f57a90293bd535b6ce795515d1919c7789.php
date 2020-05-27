<?php

/* @admin/layout.twig */
class __TwigTemplate_012f5367666c5ea12add49d5c810b71973385987eb34191631a6183227ac621f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
  <title>";
        // line 4
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
  <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css\"
        integrity=\"sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi\" crossorigin=\"anonymous\">
  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/flatpickr/3.0.7/flatpickr.css\">
  <style>
    body {
      padding-top: 5rem;
    }
  </style>
</head>
<body>

<nav class=\"navbar navbar-fixed-top navbar-dark bg-inverse\">
  <a class=\"navbar-brand\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("admin"), "html", null, true);
        echo "\">Administration</a>
  <ul class=\"nav navbar-nav\">
    ";
        // line 19
        echo $this->env->getExtension('App\Admin\AdminTwigExtension')->renderMenu();
        echo "
  </ul>
</nav>

<div class=\"container\">

  ";
        // line 25
        if ($this->env->getExtension('Framework\Twig\FlashExtension')->getFlash("success")) {
            // line 26
            echo "    <div class=\"alert alert-success\">
      ";
            // line 27
            echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Twig\FlashExtension')->getFlash("success"), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 30
        echo "
  ";
        // line 31
        if ($this->env->getExtension('Framework\Twig\FlashExtension')->getFlash("error")) {
            // line 32
            echo "    <div class=\"alert alert-danger\">
      ";
            // line 33
            echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Twig\FlashExtension')->getFlash("error"), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 36
        echo "
  ";
        // line 37
        $this->displayBlock('body', $context, $blocks);
        // line 38
        echo "
</div><!-- /.container -->

<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js\"
        integrity=\"sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK\"
        crossorigin=\"anonymous\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/flatpickr/3.0.7/flatpickr.js\"></script>
<script>
  flatpickr('.datepicker', {
    enableTime: true,
    altInput: true,
    altFormat: 'j F Y, H:i',
    dateFormat: 'Y-m-d H:i:S'
  })
</script>
</body>
</html>";
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo "Mon site ";
    }

    // line 37
    public function block_body($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "@admin/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  112 => 37,  106 => 4,  86 => 38,  84 => 37,  81 => 36,  75 => 33,  72 => 32,  70 => 31,  67 => 30,  61 => 27,  58 => 26,  56 => 25,  47 => 19,  42 => 17,  26 => 4,  21 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@admin/layout.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\src\\Admin\\views\\layout.twig");
    }
}
