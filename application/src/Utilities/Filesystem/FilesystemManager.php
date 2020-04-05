<?php

declare(strict_types=1);

namespace App\Utilities\Filesystem;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FilesystemManager
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(
        ParameterBagInterface $parameterBag,
        TokenStorageInterface $tokenStorage,
        Filesystem $filesystem
    )
    {
        $this->parameterBag = $parameterBag;
        $this->tokenStorage = $tokenStorage;
        $this->filesystem = $filesystem;
    }

    public function getProjectDir(): string
    {
        return $this->parameterBag->get('kernel.project_dir');
    }

    public function getVarDirectory(): string
    {
        $baseDirectory = $this->parameterBag->get('kernel.project_dir');

        return str_replace(
            ' ',
            '',
            sprintf($baseDirectory . '%1$s var', DIRECTORY_SEPARATOR)
        );
    }

    /**
     * Creates folder as userId name
     *
     * @param string $basePath
     * @return string
     */
    public function createUserFolder(string $basePath): string
    {
        if (!$this->filesystem->exists($this->getUserFolderFullPath($basePath))) {
            $this->filesystem->mkdir($this->getUserFolderFullPath($basePath));
        }

        return $this->getUserFolderFullPath($basePath);
    }

    private function getUserFolderFullPath($basePath): string
    {
        return rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR .
            $this->tokenStorage->getToken()->getUser()->getId();
    }
}
