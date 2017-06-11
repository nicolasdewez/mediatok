<?php

namespace AppBundle\Service;

use AppBundle\Exception\ProtocolSearchInvalidException;
use AppBundle\Exception\SshException;
use AppBundle\Model\File;
use AppBundle\Model\SearchMedia;

class SftpFinderMedia extends FinderMedia
{
    /**
     * @param SearchMedia $searchMedia
     *
     * @return array
     *
     * @throws ProtocolSearchInvalidException
     * @throws SshException
     */
    public function execute(SearchMedia $searchMedia): array
    {
        if (SearchMedia::PROTOCOL_SFTP !== $searchMedia->getProtocol()) {
            throw new ProtocolSearchInvalidException(sprintf('Protocol %s not compatible', $searchMedia->getProtocol()));
        }

        if (false === ($connection = ssh2_connect($searchMedia->getHost(), $searchMedia->getPort()))) {
            throw new SshException(sprintf(
                'Can\'t connect to SSH server (host, port) (%s, %d).',
                $searchMedia->getHost(),
                $searchMedia->getPort()
            ));
        }

        if (false === ssh2_auth_password($connection, $searchMedia->getUsername(), $searchMedia->getPassword())) {
            throw new SshException(sprintf('Can\'t log to SFTP server with username %s.', $searchMedia->getUsername()));
        }

        if (false === $sftp = ssh2_sftp($connection)) {
            throw new SshException('Could not initialize SFTP subsystem');
        }

        $this->logger->info(sprintf(
            'Connected to %s://%s:%d with username %s',
            $searchMedia->getProtocol(),
            $searchMedia->getHost(),
            $searchMedia->getPort(),
            $searchMedia->getUsername()
        ));

        fopen(
            sprintf(
                '%s://%d/./',
                $searchMedia->getProtocol(),
                intval($sftp)
            ),
            'r'
        );

        $elements = $this->searchFilesSftp($searchMedia, $sftp, $searchMedia->getDirectory());

        return $elements;
    }

    /**
     * @param SearchMedia $searchMedia
     * @param resource    $sftp
     * @param string      $directory
     *
     * @return array
     */
    private function searchFilesSftp(SearchMedia $searchMedia, $sftp, string $directory): array
    {
        $this->logger->info(sprintf(
            'Search elements into %s (recursive: %s)',
            $directory,
            $searchMedia->isRecursive()
        ));

        $results = [];

        $elements = array_diff(
            scandir(sprintf(
                '%s://%d%s',
                $searchMedia->getProtocol(),
                intval($sftp),
                $directory
            )),
            ['..', '.']
        );

        foreach ($elements as $element) {
            $isDir = @opendir(sprintf(
                '%s://%d%s/%s',
                $searchMedia->getProtocol(),
                intval($sftp),
                $directory,
                $element
            ));

            // File
            if (false === $isDir) {
                $this->logger->info(sprintf('Add file %s in elements', $element));
                $results[] = new File($element);
                continue;
            }

            // Directory but search not recursive
            if (false === $searchMedia->isRecursive()) {
                $this->logger->info(sprintf('Add directory %s in elements', $element));
                $results[] = new File($element, true);
                continue;
            }

            // Elements in directory
            $this->logger->info(sprintf('Add directory %s in elements', $element));

            $results[] = new File(
                $element,
                true,
                $this->searchFilesSftp($searchMedia, $sftp, sprintf('%s/%s', $directory, $element))
            );
        }

        return $results;
    }
}
