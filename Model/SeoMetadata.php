<?php

namespace Pellr\CmsBundle\Model;

use Burgov\Bundle\KeyValueFormBundle\KeyValueContainer;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

class SeoMetadata implements SeoMetadataInterface, TranslatableInterface
{
    /**
     * For translatable metadata.
     */
    protected $locale = 'en';

    /**
     * This string contains the information where we will find the original content.
     * Depending on the setting for the cmf_seo.original_route_pattern, it
     * will do a redirect to this url or create a canonical link with this
     * value as the href attribute.
     *
     * @var string
     */
    protected $originalUrl;

    /**
     * If this string is set, it will be inserted as a meta tag for the page description.
     *
     * @var  string
     */
    protected $metaDescription;

    /**
     * This comma separated list will contain the keywords for the page's meta information.
     *
     * @var string
     */
    protected $metaKeywords;

    /**
     * @var string
     */
    protected $title;

    /**
     * To store meta tags for type property.
     *
     * @var array
     */
    protected $extraProperties = array();

    /**
     * To store extra meta tags for type name.
     *
     * @var array
     */
    protected $extraNames = array();

    /**
     * To store meta tags for type http-equiv.
     *
     * @var array
     */
    protected $extraHttp = array();

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * {@inheritDoc}
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtraProperties($extraProperties)
    {
        $this->extraProperties = $this->toArray($extraProperties);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraProperties()
    {
        return $this->extraProperties;
    }

    /**
     * {@inheritDoc}
     */
    public function addExtraProperty($key, $value)
    {
        $this->extraProperties[$key] = (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraProperty($key)
    {
        if (array_key_exists($key, $this->extraProperties)) {
            unset($this->extraProperties[$key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setExtraNames($extraNames)
    {
        $this->extraNames = $this->toArray($extraNames);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraNames()
    {
        return $this->extraNames;
    }

    /**
     * {@inheritDoc}
     */
    public function addExtraName($key, $value)
    {
        $this->extraNames[$key] = (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraName($key)
    {
        if (array_key_exists($key, $this->extraNames)) {
            unset($this->extraNames[$key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setExtraHttp($extraHttp)
    {
        $this->extraHttp = $this->toArray($extraHttp);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraHttp()
    {
        return $this->extraHttp;
    }

    /**
     * {@inheritDoc}
     */
    public function addExtraHttp($key, $value)
    {
        $this->extraHttp[$key] = (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraHttp($key)
    {
        if (array_key_exists($key, $this->extraHttp)) {
            unset($this->extraHttp[$key]);
        }
    }

    /**
     * Extract an array out of $data or throw an exception if not possible.
     *
     * @param array|KeyValueContainer|\Traversable $data Something that can be converted to an array.
     *
     * @return array Native array representation of $data
     *
     * @throws InvalidArgumentException If $data can not be converted to an array.
     */
    protected function toArray($data)
    {
        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof KeyValueContainer) {
            return $data->toArray();
        }

        if ($data instanceof \Traversable) {
            return iterator_to_array($data);
        }

        throw new InvalidArgumentException(sprintf('Expected array, Traversable or KeyValueContainer, got "%s"', is_object($data) ? getclass($data) : get_type($data)));
    }
}
