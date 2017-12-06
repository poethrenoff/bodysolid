<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Site as SiteEntity;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SiteStorage
{
    /**
     * @var SiteEntity
     */
    protected $site;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * SiteStorage constructor
     *
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container,
                                EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * Get site
     *
     * @throws \Exception
     *
     * @return SiteEntity
     */
    public function get()
    {
        if (empty($this->site)) {
            $siteName = $this->container->getParameter('site');
            $this->site = $this->entityManager->getRepository(SiteEntity::class)->findOneByName($siteName);
            if (!$this->site) {
                throw new \Exception(sprintf('Site "%s" not found', $siteName));
            }
        }

        return $this->site;
    }
}