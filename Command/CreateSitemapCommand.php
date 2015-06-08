<?php

namespace Pellr\CmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser as YamlParser;

class CreateSitemapCommand extends ContainerAwareCommand
{
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    protected $dm;

    /**
     * @var string
     */
    protected $pageBasepath;

    /**
     * @var string
     */
    protected $pageClass;

    /**
     * @var string
     */
    protected $sitemapPath;

    protected function configure()
    {
        $this
            ->setName('pellr:cms:create_sitemap')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $this->dm = $container->get('doctrine_phpcr.odm.document_manager');
        $this->pageBasepath = $container->getParameter('pellr_cms.persistence.phpcr.basepath');
        $this->pageClass = $container->getParameter('pellr_cms.persistence.phpcr.document.class');
        $this->sitemapPath = $container->getParameter('pellr_cms.sitemap.path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yamlParser = new YamlParser();
        $sitemap = $yamlParser->parse(file_get_contents($this->sitemapPath));

        $this->upsertPages($this->pageBasepath, $sitemap);
        $this->dm->flush();
    }

    protected function upsertPages($basepath, array $pages)
    {
        foreach ($pages as $slug => $data) {
            $this->upsertPage($basepath, $slug, $data);

            if (isset($data['children'])) {
                $this->upsertPages($basepath.'/'.$slug, $data['children']);
            }
        }
    }

    protected function upsertPage($basepath, $slug, array $data = null)
    {
        $data = $data ?: [];
        $id = $basepath.'/'.$slug;
        $label = isset($data['label']) ? $data['label'] : ucwords(str_replace('-', ' ', $slug));

        /** @var \Pellr\CmsBundle\Doctrine\Phpcr\Page $page */
        $page = $this->dm->find($this->pageClass, $id);
        if (!$page) {
            $page = new $this->pageClass;
            $page->setId($id);
            $page->setSeoMetadata(new \Pellr\CmsBundle\Doctrine\Phpcr\SeoMetadata());
        }

        $data = array_merge([
            'label' => $label,
            'title' => $label,
            'body' => sprintf('Page content for "%s"', $label),
        ], $data);

        foreach ($data as $key => $value) {
            if (in_array($key, ['children'])) {
                continue;
            }
            if ($value) {
                $setter = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                if (!method_exists($page, $setter)) {
                    throw new \InvalidArgumentException('Invalid Page option: '.$key);
                }
                $page->$setter($value);
            }
        }

        $this->dm->persist($page);
    }
}
