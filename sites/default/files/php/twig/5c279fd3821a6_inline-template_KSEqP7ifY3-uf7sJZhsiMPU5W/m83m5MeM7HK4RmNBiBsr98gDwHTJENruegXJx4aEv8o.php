<?php

/* {# inline_template_start #}<a href="#" role="button" aria-label="{{ format_text }}"><span class="ckeditor-button-dropdown">{{ format_text }}<span class="ckeditor-button-arrow"></span></span></a> */
class __TwigTemplate_a3bcb4a76d98baecb30378c1867c0aea75d18cf970124986d8db5289253f7bbf extends Twig_Template
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
        $tags = array();
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array(),
                array(),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 1
        echo "<a href=\"#\" role=\"button\" aria-label=\"";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["format_text"] ?? null), "html", null, true));
        echo "\"><span class=\"ckeditor-button-dropdown\">";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["format_text"] ?? null), "html", null, true));
        echo "<span class=\"ckeditor-button-arrow\"></span></span></a>";
    }

    public function getTemplateName()
    {
        return "{# inline_template_start #}<a href=\"#\" role=\"button\" aria-label=\"{{ format_text }}\"><span class=\"ckeditor-button-dropdown\">{{ format_text }}<span class=\"ckeditor-button-arrow\"></span></span></a>";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "{# inline_template_start #}<a href=\"#\" role=\"button\" aria-label=\"{{ format_text }}\"><span class=\"ckeditor-button-dropdown\">{{ format_text }}<span class=\"ckeditor-button-arrow\"></span></span></a>", "");
    }
}
