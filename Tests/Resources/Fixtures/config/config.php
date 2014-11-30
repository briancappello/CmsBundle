<?php

$container->loadFromExtension(\Pellr\CmsBundle\DependencyInjection\PellrCmsExtension::ALIAS, array(
    'persistence' => array(
        'phpcr' => array(
            'enabled' => true,
            'basepath' => \Pellr\CmsBundle\DependencyInjection\PellrCmsExtension::PHPCR_BASEPATH,
            'manager_registry' => 'doctrine_phpcr',
            'manager_name' => null,
            'document_class' => 'Pellr\CmsBundle\Doctrine\Phpcr\Page',
            'use_sonata_admin' => true,
            'sonata_admin' => array(
                'sort' => 'asc',
            )
        ),
    ),
    'use_menu' => false,
));
