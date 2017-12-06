<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Service\SiteStorage;
use AppBundle\Entity\Text;

class TextStorage
{
    /**
     * @var SiteStorage
     */
    protected $siteStorage;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $texts;

    /**
     * TextStorage constructor
     *
     * @param SiteStorage $siteStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SiteStorage $siteStorage,
                                EntityManagerInterface $entityManager)
    {
        $this->siteStorage = $siteStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * Get text
     *
     * @throws \Exception
     *
     * @param  string $name
     * @param  string|null $default
     *
     * @return string
     */
    public function get(string $name, string $default = null)
    {
        if (empty($this->texts)) {
            $texts = $this->entityManager->getRepository(Text::class)->findBy([
                'site' => $this->siteStorage->get()->getId()
            ]);
            $commonTexts = $this->entityManager->getRepository(Text::class)->findBy([
                'site' => null
            ]);

            $this->texts = array_reduce($commonTexts, function ($carry, $item) {
                return $carry + [$item->getName() => $item];
            }, array_reduce($texts, function ($carry, $item) {
                return $carry + [$item->getName() => $item];
            }, []));
        }

        return $this->texts[$name] ?? ($default ?? (new Text()));
    }
}