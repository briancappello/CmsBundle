<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle\Tests\Unit\Doctrine\Phpcr;

use Pellr\CmsBundle\Doctrine\Phpcr\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing BC
     */
    public function testGetSet()
    {
        $page = new Page;

        $page->setAddLocalePattern(true);
        $this->assertEquals(true, $page->getAddLocalePattern());
    }
}
