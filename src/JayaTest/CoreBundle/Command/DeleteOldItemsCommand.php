<?php

namespace JayaTest\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteOldItemsCommand
 * @package JayaTest\CoreBundle\Command
 */
class DeleteOldItemsCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected function configure()
    {
        $this->setName('jayatest:delete-old-items')
            ->setDescription('Delete old items')
            ->addOption('count', null, InputOption::VALUE_REQUIRED, 'Items count for deleting');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_numeric($input->getOption('count')) && $input->getOption('count') == 0) {
            throw new \InvalidArgumentException('Count value must be an integer and more than 0');
        }

        /*
        такой вариант не работает, т.к. параметры можем передавать для столбцов. Будучи уверенным, что count - число (мы проверили это выше) - используем его напрямую

        $result = $this->em->getConnection()->executeQuery('DELETE FROM item ORDER BY created_at ASC limit :limit', [
                'limit' => $input->getOption('count')
            ]);

        вариант отлично бы сработал в случае удаления записей, добавленных раньше конкретной даты

        $date = $input->getOption('from_date');
        $result = $this->em->getConnection()->executeQuery('DELETE FROM item WHERE created_at < :date', [
                'date' => $date->format('Y-m-d H:i:s'),
            ]);
        */

        $result = $this->em->getConnection()->executeQuery(
            sprintf('DELETE FROM item ORDER BY created_at ASC limit %d', $input->getOption('count'))
        );

        $output->writeln(
            sprintf('<comment>Deleted items: %d</comment>', $result->rowCount())
        );
    }
}