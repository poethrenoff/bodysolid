<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use AppBundle\Entity\Preference;

/**
 * Class RateUpdateCommand
 */
class RateUpdateCommand extends DoctrineCommand
{
    /**
     * @var string
     */
    const RATES_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:rate:update');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rates = @simplexml_load_file(self::RATES_URL);
        if (!$rates) {
            return;
        }

        $dollar = $rates->xpath('Valute[CharCode="USD"]');
        if (!$dollar) {
            return;
        }

        $value = (string) $dollar[0]->Value;
        $value = str_replace(',', '.', $value);

        $entityManager = $this->getEntityManager('default');
        $rate = $entityManager->getRepository(Preference::class)->findOneByName('rate');
        $rate->setValue($value);
        $entityManager->flush();
    }
}