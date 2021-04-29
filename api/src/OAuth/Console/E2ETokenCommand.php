<?php

declare(strict_types=1);

namespace App\OAuth\Console;

use App\OAuth\Entity\Scope;
use App\OAuth\Generator\AccessTokenGenerator;
use App\OAuth\Generator\Params;
use DateTimeImmutable;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class E2ETokenCommand extends Command
{
    private ClientRepositoryInterface $clients;
    private AccessTokenGenerator $generator;

    public function __construct(ClientRepositoryInterface $clients, AccessTokenGenerator $generator)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->generator = $generator;
    }

    protected function configure(): void
    {
        $this
            ->setName('oauth:e2e-token')
            ->setDescription('Generate E2E-test auth token')
            ->addArgument('client-id', InputArgument::OPTIONAL)
            ->addArgument('scopes', InputArgument::OPTIONAL)
            ->addArgument('user-id', InputArgument::OPTIONAL)
            ->addArgument('role', InputArgument::OPTIONAL);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        while (empty($input->getArgument('client-id'))) {
            $input->setArgument(
                'client-id',
                (string)$helper->ask($input, $output, new Question('Client (frontend): ', 'frontend'))
            );
        }

        while (empty($input->getArgument('scopes'))) {
            $input->setArgument(
                'scopes',
                (string)$helper->ask($input, $output, new Question('Scopes (common): ', 'common'))
            );
        }

        while (empty($input->getArgument('user-id'))) {
            $input->setArgument(
                'user-id',
                (string)$helper->ask($input, $output, new Question('User Id: '))
            );
        }

        while (empty($input->getArgument('role'))) {
            $input->setArgument(
                'role',
                (string)$helper->ask($input, $output, new Question('Role (user): ', 'user'))
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $clientId */
        $clientId = $input->getArgument('client-id');
        /** @var string $scopes */
        $scopes = $input->getArgument('scopes');
        /** @var string $userId */
        $userId = $input->getArgument('user-id');
        /** @var string $role */
        $role = $input->getArgument('role');

        $client = $this->clients->getClientEntity($clientId);

        if ($client === null) {
            $output->writeln('<error>Invalid client ' . $clientId . '</error>');
            return 1;
        }

        $token = $this->generator->generate(
            $client,
            array_map(
                static fn (string $name) => new Scope($name),
                explode(' ', $scopes)
            ),
            new Params(
                userId: $userId,
                role: $role,
                expires: new DateTimeImmutable('+1000 years')
            )
        );

        $output->writeln((string)$token);
        return 0;
    }
}
