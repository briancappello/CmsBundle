<?php

namespace Pellr\CmsBundle\Initializer;

use Pellr\CmsBundle\Doctrine\Phpcr\Page;
use Pellr\CmsBundle\Doctrine\Phpcr\SeoMetadata;

use Doctrine\Bundle\PHPCRBundle\Initializer\InitializerInterface;
use Doctrine\ODM\PHPCR\DocumentManager;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use PHPCR\Util\PathHelper;
use PHPCR\Util\NodeHelper;

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

        $session = $dm->getPhpcrSession();

        if (!$root = $dm->find(null, $this->basePath)) {
            $root = NodeHelper::createPath($session, PathHelper::getParentPath($this->basePath));
        }

        /** @var Page $homepage */
        $homepage = new $this->documentClass;
        $homepage->setParentDocument($root);
        $homepage->setName('home');
        $homepage->setLabel('Home');
        $homepage->setTitle('Home');
        $homepage->setBody('Homepage content.');

        $seo = new SeoMetadata();
        $seo->setTitle('Home');
        $homepage->setSeoMetadata($seo);

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
