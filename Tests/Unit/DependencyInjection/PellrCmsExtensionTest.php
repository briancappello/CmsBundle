<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Pellr\CmsBundle\DependencyInjection\PellrCmsExtension;

class PellrCmsExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    {
        return array(
            new PellrCmsExtension(),
        );
    }

    public function testLoadDefault()
    {
        $this->container->setParameter('kernel.bundles', array('CmfRoutingBundle' => true, 'SonataDoctrinePHPCRAdminBundle' => true, 'CmfMenuBundle' => true));

        $this->load(array(
            'persistence' => array(
                'phpcr' => array(
                    'enabled' => true,
                ),
            ),
        ));

        $this->assertContainerBuilderHasService(PellrCmsExtension::ALIAS.'.initializer', 'Pellr\SimpleCms\HomepageInitializer');
        $this->assertContainerBuilderHasService(PellrCmsExtension::ALIAS.'.persistence.phpcr.migrator.page', 'Pellr\CmsBundle\Migrator\Phpcr\Page');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(PellrCmsExtension::ALIAS.'.persistence.phpcr.menu_provider', 'setManagerName', array(
            '%cmf_simple_cms.persistence.phpcr.manager_name%',
        ));
        $this->assertContainerBuilderHasService(PellrCmsExtension::ALIAS.'.persistence.phpcr.admin.page', 'Pellr\CmsBundle\Admin\PageAdmin');
    }

    public function testLoadMinimal()
    {
        $this->load(array(
            'persistence' => array(
                'phpcr' => array(
                    'enabled' => true,
                    'use_sonata_admin' => false,
                ),
            ),
            'use_menu' => false,
        ));

        $this->assertFalse($this->container->has(PellrCmsExtension::ALIAS.'.persistence.phpcr.admin.page'));
        $this->assertFalse($this->container->has(PellrCmsExtension::ALIAS.'.persistence.phpcr.menu_provider'));
    }
}
