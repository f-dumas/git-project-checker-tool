<?php

namespace FDTool\GitChecker\FileParser;

use Symfony\Component\Finder\Finder;

class GitDirectoryParser
{

    public static function parseFoldersToGetGitRepositories(string $pathToCheck): array
    {
        $foldersToCheck = [];
        $finder = new Finder();
        $finder->directories()
            ->ignoreVCS(false)
            ->ignoreDotFiles(false)
            ->in($pathToCheck)
            ->path("#.git#")
            ->depth('< 3');

        foreach ($finder as $file) {
            if ($file->getBasename() === ".git" && $file->isReadable()) {
                $folderToAdd = preg_replace('#^(.*/)\.git$#', "$1", $file->getRealPath());
                $foldersToCheck[] = $folderToAdd;
            }
        }
        return $foldersToCheck;
    }
}
