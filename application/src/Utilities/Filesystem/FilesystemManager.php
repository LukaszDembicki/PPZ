<?php

declare(strict_types=1);

namespace App\Utilities\Filesystem;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemManager
{
    private ParameterBagInterface $parameterBag;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    public function __construct(
        ParameterBagInterface $parameterBag,
        Filesystem $filesystem
    )
    {
        $this->parameterBag = $parameterBag;
        $this->filesystem = $filesystem;
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
}
