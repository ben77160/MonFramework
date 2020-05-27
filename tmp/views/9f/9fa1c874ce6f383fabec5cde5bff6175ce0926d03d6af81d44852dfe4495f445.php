<?php

/* layout.twig */
class __TwigTemplate_a1b8de49e15b4a06f4f7a8321b4aa32cd0c893fd2a389512e04b123e7140c2d5 extends Twig_Template
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
    <style>
        body {
            padding-top: 5rem;
        }
    </style>
</head>
<body>

<nav class=\"navbar navbar-fixed-top navbar-dark bg-inverse\">
    <a class=\"navbar-brand\" href=\"#\">Mon super site</a>
    <ul class=\"nav navbar-nav\">
        <li class=\"nav-item active\">
            <a class=\"nav-link\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('Framework\Router\RouterTwigExtension')->pathFor("blog.index"), "html", null, true);
        echo "\">Blog</a>
        </li>
    </ul>
</nav>

<div class=\"container\">

    ";
        // line 26
        $this->displayBlock('body', $context, $blocks);
        // line 27
        echo "
</div><!-- /.container -->

<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js\"
        integrity=\"sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK\"
        crossorigin=\"anonymous\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/timeago.js/3.0.2/timeago.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/timeago.js/3.0.2/timeago.locales.min.js\"></script>
<script>
    timeago().render(document.querySelectorAll('.timeago'), 'fr')
</script>
</body>
</html>";
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo "Mon site ";
    }

    // line 26
    public function block_body($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 26,  72 => 4,  56 => 27,  54 => 26,  44 => 19,  26 => 4,  21 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout.twig", "C:\\Users\\Benjamin Nsingi\\Documents\\Mon Portfolio\\MonFramework\\views\\layout.twig");
    }
}
