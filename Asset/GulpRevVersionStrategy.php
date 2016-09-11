<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Irozgar\GulpRevVersionsBundle\Asset;

use Exception;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Class VersionStrategy
 *
 * @author Isaac Rozas García <irozgar@gmail.com>
 */
class GulpRevVersionStrategy implements VersionStrategyInterface
{
    private $manifestFilename;

    private $manifestPath;

    private $paths = array();

    private $kernelRootDir;

    /**
     * VersionStrategy constructor.
     *
     * @param string $kernelRootDir
     * @param string $manifestPath
     */
    public function __construct($kernelRootDir, $manifestPath)
    {
        $this->manifestPath = $manifestPath;
        $this->kernelRootDir = $kernelRootDir;
    }

    public function getVersion($path)
    {
        if (file_exists($path)) {
            return null;
        }

        $path = pathinfo($this->getAssetVersion($path));
        $filenameParts = explode('-', $path['filename']);

        // With gulp rev, the version is at the end of the filename so it will be the last item of the array
        return $filenameParts[count($filenameParts) - 1];
    }

    public function applyVersion($path)
    {
        return $this->getAssetVersion($path);
    }

    private function getAssetVersion($path)
    {
        // The twig extension is a singleton so we store the loaded content into a property to read it only once
        // @see https://knpuniversity.com/screencast/gulp/version-cache-busting#comment-2884388919
        if (count($this->paths) === 0) {
            $this->loadManifestFile();
        }

        // If a file exists, it doesn't have a version so we ignore it
        $fileExists = file_exists($this->kernelRootDir . '/../web/' . $path);
        $hasVersion = isset($this->paths[$path]);

        if (!$fileExists && !$hasVersion) {
            throw new Exception(sprintf('The file "%s" does not exist and there is no version file for it', $path));
        }

        return $hasVersion ? $this->paths[$path] : $path;
    }

    private function loadManifestFile()
    {
        $manifestPath = $this->kernelRootDir . '/' . $this->manifestPath;

        if (!is_file($manifestPath)) {
            throw new Exception(
                sprintf(
                    'Manifest file "%s" not found in path "%s". You can generate this file running gulp',
                    $this->manifestFilename, $this->manifestPath
                )
            );
        }

        $this->paths = json_decode(file_get_contents($manifestPath), true);
    }
}
