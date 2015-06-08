<?php

namespace Pellr\CmsBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\HierarchyInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Pellr\CmsBundle\Model\SeoMetadata as SeoMetadataModel;

class SeoMetadata extends SeoMetadataModel implements HierarchyInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name = 'seoMetadata';

    /**
     * @var object
     */
    protected $parentDocument;

    public $node;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return SeoMetadata
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setParentDocument($parentDocument)
    {
        $this->parentDocument = $parentDocument;

        return $this;
    }

    /**
     * @deprecated use setParentDocument
     */
    public function setParent($parent)
    {
        return $this->setParentDocument($parent);
    }

    /**
     * {@inheritDoc}
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * @deprecated use getParentDocument
     */
    public function getParent()
    {
        return $this->getParentDocument();
    }
}
