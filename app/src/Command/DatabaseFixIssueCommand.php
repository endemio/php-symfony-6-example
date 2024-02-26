<?php


namespace App\Command;

use App\Entity\SessionMembers;
use App\Entity\Sessions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-fix',
    description: 'Fixing DB issues',
)]
class DatabaseFixIssueCommand extends EntityListCommand
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $sessions = $this->entityManager->getRepository(Sessions::class)->findAll();

        $originals = []; // Array of all unique "sessions"
        $duplicates = []; // Array of "session_ids" (as keys) with original session_ids (as values)

        $unique_key = function (Sessions $session) {
            return $session->getStartTime()->format('Y-m-d H:i:s') . '_' . $session->getSessionConfigurationId();
        };

        // Fill array of original and duplicates
        /** @var Sessions $session */
        foreach ($sessions as $session) {
            if (!in_array($unique_key($session), array_keys($originals))) {
                $originals[$unique_key($session)] = $session;
            } else {
                $duplicates[$session->getId()] = $originals[$unique_key($session)];
            }
        }

        // Show sessions in 2 tables
        echo '==== Original ====', PHP_EOL;
        $this->drawTable(['id', 'start_time', 'session_configuration_id'], array_map(function (Sessions $item) {
            return ['id' => $item->getId(),
                'start_time' => $item->getStartTime()->format('Y-m-d H:i:s'),
                'session_configuration_id' => $item->getSessionConfigurationId()];
        }, $originals));

        echo PHP_EOL,'==== Duplicates ====',PHP_EOL;
        $this->drawTable(['id', 'start_time', 'session_configuration_id'], array_map(function (int $id, Sessions $item) {
            return ['id' => $id,
                'start_time' => $item->getStartTime()->format('Y-m-d H:i:s'),
                'session_configuration_id' => $item->getSessionConfigurationId()];
        }, array_keys($duplicates), array_values($duplicates)));

        // For session_members fix 'session' from duplicate session to original session
        $session_members = $this->entityManager->getRepository(SessionMembers::class)->findAll();
        /** @var SessionMembers $item */
        foreach ($session_members as $item) {
            if (in_array($item->getSession()->getId(), array_keys($duplicates))) {
                $item->setSession($originals[$unique_key($item->getSession())]);
                $this->entityManager->persist($item);
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        return Command::SUCCESS;
    }

}