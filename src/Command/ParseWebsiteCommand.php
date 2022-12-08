<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\CrawlerRequestException;
use App\Service\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ParseWebsiteCommand extends Command
{
    public function __construct(private readonly Crawler $crawler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('url', mode: InputOption::VALUE_REQUIRED, description: 'Url of website that you want to parse.')
            ->addOption('class', mode: InputOption::VALUE_REQUIRED, description: 'Html class of elements that you want find.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->crawler->getHtmlElementsByClassFromWebsite(
                $input->getOption('url'),
                $input->getOption('class')
            );

            /** @var string $responseJson */
            $responseJson = \json_encode($response);
            $output->writeln($responseJson);
        } catch (CrawlerRequestException $e) {
            $output->writeln(\sprintf('<error>%s</error>', $e));

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
