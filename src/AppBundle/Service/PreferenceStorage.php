<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Service\SiteStorage;
use AppBundle\Entity\Preference;

class PreferenceStorage
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
    protected $preferences;

    /**
     * PreferenceStorage constructor
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
     * Get preference
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
        if (empty($this->preferences)) {
            $preferences = $this->entityManager->getRepository(Preference::class)->findBy([
                'site' => $this->siteStorage->get()->getId()
            ]);
            $commonPreferences = $this->entityManager->getRepository(Preference::class)->findBy([
                'site' => null
            ]);

            $this->preferences = array_reduce($commonPreferences, function ($carry, $item) {
                return $carry + [$item->getName() => $item->getValue()];
            }, array_reduce($preferences, function ($carry, $item) {
                return $carry + [$item->getName() => $item->getValue()];
            }, []));
        }

        return $this->preferences[$name] ?? $default;
    }
}