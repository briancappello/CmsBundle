<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle;

use Pellr\CmsBundle\DependencyInjection\PellrCmsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass;

class PellrCmsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        if ($container->hasExtension('jms_di_extra')) {
            $container->getExtension('jms_di_extra')->blackListControllerFile(__DIR__ . '/Controller/PageAdminController.php');
        }

        if (class_exists('Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass')) {
            $container->addCompilerPass(
                DoctrinePhpcrMappingsPass::createXmlMappingDriver(
                    array(
                        realpath(__DIR__ . '/Resources/config/doctrine-model') => 'Pellr\CmsBundle\Model',
                        realpath(__DIR__ . '/Resources/config/doctrine-phpcr') => 'Pellr\CmsBundle\Doctrine\Phpcr',
                    ),
                    array(PellrCmsExtension::ALIAS.'.persistence.phpcr.manager_name'),
                    false,
                    array('PellrCmsBundle' => 'Pellr\CmsBundle\Doctrine\Phpcr')
                )
            );
        }
    }
}
