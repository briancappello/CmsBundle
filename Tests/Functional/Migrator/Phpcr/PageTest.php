<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle\Tests\Functional\Migrator\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use Pellr\CmsBundle\DependencyInjection\PellrCmsExtension;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Pellr\CmsBundle\Migrator\Phpcr\Page;

class PageTest extends BaseTestCase
{
    /**
     * @var Page
     */
    private $migrator;

    /**
     * @var DocumentManager
     */
    private $dm;

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->baseNode = $this->dm->find(null, '/test');
        $this->migrator = $this->getContainer()->get(PellrCmsExtension::ALIAS.'.persistence.phpcr.migrator.page');
    }

    public function testMigrator()
    {
        $this->migrator->migrate('/test/page');
        $this->migrator->migrate('/test/page/foo');

        $res = $this->dm->find(null, '/test/page');

        $this->assertNotNull($res);
        $this->assertEquals('Test', $res->getTitle());

        $res = $this->dm->find(null, '/test/page/foo');

        $this->assertNotNull($res);
        $this->assertEquals('Foobar', $res->getTitle());
    }
}
