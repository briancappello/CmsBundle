<?php

namespace Pellr\CmsBundle\Twig;

class PellrCmsTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('strip_empty_tags', array($this, 'stripEmptyTags'), array('is_safe' => array('html'))),
        );
    }

    public function stripEmptyTags($string)
    {
        return trim(preg_replace('#\<\w+>\s*\</\w+>#', '', $string));
    }

    public function getName()
    {
        return 'pellr_cms_twig_extension';
    }
}
