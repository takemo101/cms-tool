<?php

namespace CmsTool\Support\Dotenv;

use RuntimeException;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Support\ApplicationPath;
use Takemo101\Chubby\Support\ApplicationSummary;

class LocalFileDotenvContentRepository implements DotenvContentRepository
{
    public const DotenvName = '.env';

    /**
     * constructor
     *
     * @param ApplicationPath $path
     * @param ApplicationSummary $summary
     * @param LocalFilesystem $filesystem
     */
    public function __construct(
        private readonly ApplicationPath $path,
        private readonly ApplicationSummary $summary,
        private readonly LocalFilesystem $filesystem,
    ) {
        //
    }

    /**
     * Find the dotenv content.
     *
     * @return DotenvContent|null
     * @throws RuntimeException
     */
    public function find(): ?DotenvContent
    {
        $paths = $this->createDotenvPaths();

        foreach ($paths as $path) {
            if ($this->filesystem->exists($path)) {
                return new DotenvContent(
                    $this->filesystem->read($path) ?? throw new RuntimeException("Failed to read the dotenv file: {$path}"),
                );
            }
        }

        return null;
    }

    /**
     * Save the dotenv content.
     *
     * @param DotenvContent $content
     * @return void
     * @throws RuntimeException
     */
    public function save(DotenvContent $content): void
    {
        $paths = $this->createDotenvPaths();

        foreach ($paths as $path) {
            if ($this->filesystem->exists($path)) {
                $this->filesystem->write($path, $content->value()) ?: throw new RuntimeException("Failed to write the dotenv file: {$path}");
            }
        }
    }

    /**
     * Create dotenv paths.
     *
     * @return string[]
     */
    private function createDotenvPaths(): array
    {
        $name = self::DotenvName;
        $enviroment = $this->summary->getEnvironment();

        return [
            $this->path->getBasePath($name),
            $this->path->getBasePath("{$name}.{$enviroment}"),
        ];
    }
}
