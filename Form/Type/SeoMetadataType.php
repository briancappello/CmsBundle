<?php

namespace Pellr\CmsBundle\Form\Type;

use Symfony\Cmf\Bundle\SeoBundle\Form\Type\SeoMetadataType as BaseSeoMetadataType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeoMetadataType extends BaseSeoMetadataType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'Pellr\CmsBundle\Doctrine\Phpcr\SeoMetadata',
        ));
    }
}
