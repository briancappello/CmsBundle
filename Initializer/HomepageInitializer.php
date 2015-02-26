<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle\Initializer;

use PHPCR\Util\NodeHelper;
use PHPCR\Util\PathHelper;

use Doctrine\Bundle\PHPCRBundle\Initializer\InitializerInterface;
use Doctrine\ODM\PHPCR\DocumentManager;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;

class HomepageInitializer implements InitializerInterface
{
    private $basePath;
    private $documentClass;

    public function __construct($basePath, $documentClass)
    {
        $this->basePath = $basePath;
        $this->documentClass = $documentClass;
    }

    /**
     * {@inheritDoc}
     */
    public function init(ManagerRegistry $registry)
    {
        /** @var $dm DocumentManager */
        $dm = $registry->getManagerForClass($this->documentClass);
        if ($dm->find(null, $this->basePath)) {
            return;
        }

        $session = $dm->getPhpcrSession();
        NodeHelper::createPath($session, PathHelper::getParentPath($this->basePath));

        $homepage = new $this->documentClass;
        $homepage->setId($this->basePath);
        $homepage->setLabel('Home');
        $homepage->setTitle('Home');
        $homepage->setBody('Homepage content.');

        $dm->persist($homepage);

        $dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'PellrCmsBundle';
    }
}
