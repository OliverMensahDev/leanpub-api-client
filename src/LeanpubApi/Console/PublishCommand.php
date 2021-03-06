<?php
declare(strict_types=1);

namespace LeanpubApi\Console;

use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class PublishCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->setName('leanpub:publish')
            ->setDescription('Publish a new version of the book')
            ->addOption(
                'release-notes',
                null,
                InputOption::VALUE_REQUIRED,
                'The file path containing the release notes that should be emailed to readers'
            )
            ->addOption(
                'sleep',
                null,
                InputOption::VALUE_REQUIRED,
                'Number of seconds between asking job status (>= 5)',
                '5'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $leanpubApi = $this->leanpubApi($output);

        $releaseNotesPath = $input->getOption('release-notes');
        if (is_string($releaseNotesPath)) {
            $releaseNotes = file_get_contents($releaseNotesPath);
            if ($releaseNotes === false) {
                throw new RuntimeException(
                    'The provided release notes option does not point to an actual file: ' . $releaseNotesPath
                );
            }

            $output->writeln('Publish new version and emailing readers...');
            $leanpubApi->publishNewVersionAndEmailReaders($releaseNotes);
        } else {
            $output->writeln('Publish new version...');
            $leanpubApi->publishNewVersion();
        }

        $this->showProgress($leanpubApi, $input, $output);

        $output->writeln('Published PDF can be found at: ' . $leanpubApi->getBookSummary()->pdfPublishedUrl());

        return 0;
    }
}
