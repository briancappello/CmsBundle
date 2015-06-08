<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pellr\CmsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Cmf\Bundle\RoutingBundle\Admin\RouteAdmin;
use Pellr\CmsBundle\Doctrine\Phpcr\Page;

class PageAdmin extends RouteAdmin
{
    protected $translationDomain = 'PellrCmsBundle';

    private $sortOrder = false;

    public function setSortOrder($sortOrder)
    {
        if (! in_array($sortOrder, array(false, 'asc', 'desc'))) {
            throw new \InvalidArgumentException($sortOrder);
        }
        $this->sortOrder = $sortOrder;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('path', 'text')
            ->addIdentifier('title', 'text')
            ->add('label', 'text')
            ->add('name', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general', array(
                'translation_domain' => 'CmfRoutingBundle',
            ))
                ->add(
                    'parent',
                    'doctrine_phpcr_odm_tree',
                    array(
                        'select_root_node' => true,
                        'class' => $this->getClass(),
                        'root_node' => '/cms',
                    )
                )
                ->reorder(array('parent', 'name'))
            ->end()
        ;

        $formMapper
            ->with('form.group_general', array(
                'translation_domain' => 'PellrCmsBundle',
            ))
                ->add('label', null, array('required' => false))
                ->add('name')
                ->add('title')
                ->add('body', 'textarea')
            ->end()

            ->with('form.group_advanced', array(
                'translation_domain' => 'CmfRoutingBundle',
            ))
                ->add('variablePattern', 'text', array('required' => false), array('help' => 'form.help_variable_pattern'))
                ->add(
                    'defaults',
                    'sonata_type_immutable_array',
                    array(
                        'keys' => $this->configureFieldsForDefaults($this->getSubject()->getDefaults()),
                        'required' => false,
                    )
                )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name',  'doctrine_phpcr_nodename')
        ;
    }

    public function getExportFormats()
    {
        return array();
    }

    public function prePersist($object)
    {
        if ($this->sortOrder) {
            $this->ensureOrderByDate($object);
        }
    }

    public function preUpdate($object)
    {
        if ($this->sortOrder) {
            $this->ensureOrderByDate($object);
        }
    }

    /**
     * @param Page $page
     */
    protected function ensureOrderByDate($page)
    {
        $items = $page->getParentDocument()->getChildren();

        $itemsByDate = array();
        /** @var $item Page */
        foreach ($items as $item) {
            $itemsByDate[$item->getDate()->format('U')][$item->getPublishStartDate()->format('U')][] = $item;
        }

        if ('asc' == $this->sortOrder) {
            ksort($itemsByDate);
        } else {
            krsort($itemsByDate);
        }
        $sortedItems = array();
        foreach ($itemsByDate as $itemsForDate) {
            if ('asc' == $this->sortOrder) {
                ksort($itemsForDate);
            } else {
                krsort($itemsForDate);
            }
            foreach ($itemsForDate as $itemsForPublishDate) {
                foreach ($itemsForPublishDate as $item) {
                    $sortedItems[$item->getName()] = $item;
                }
            }
        }

        if ($sortedItems !== $items->getKeys()) {
            $items->clear();
            foreach ($sortedItems as $key => $item) {
                $items[$key] = $item;
            }
        }
    }

    public function toString($object)
    {
        return $object instanceof Page && $object->getTitle()
            ? $object->getTitle()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
        ;
    }
}
