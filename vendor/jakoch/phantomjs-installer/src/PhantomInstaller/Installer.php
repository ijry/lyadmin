<?php

/*
 * This file is part of the "jakoch/phantomjs-installer" package.
 *
 * Copyright (c) 2013-2016 Jens-André Koch <jakoch@web.de>
 *
 * The content is released under the MIT License. Please view
 * the LICENSE file that was distributed with this source code.
 */

namespace PhantomInstaller;

use Composer\Composer;
use Composer\Downloader\TransportException;
use Composer\IO\BaseIO as IO;
use Composer\Package\Package;
use Composer\Package\RootPackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Script\Event;

class Installer
{
    const PHANTOMJS_NAME = 'PhantomJS';

    const PHANTOMJS_TARGETDIR = '/jakoch/phantomjs';

    const PHANTOMJS_CHMODE = 0770; // octal !

    const PACKAGE_NAME = 'jakoch/phantomjs-installer';

    /** @var Composer */
    protected $composer;

    /** @var IO */
    protected $io;

    /**
     * @return Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @param Composer $composer
     * @return static
     */
    public function setComposer(Composer $composer)
    {
        $this->composer = $composer;
        return $this;
    }

    /**
     * @return IO
     */
    public function getIO()
    {
        return $this->io;
    }

    /**
     * @param IO $io
     * @return static
     */
    public function setIO(IO $io)
    {
        $this->io = $io;
        return $this;
    }

    public function __construct(Composer $composer, IO $io)
    {
        $this->setComposer($composer);
        $this->setIO($io);
    }

    /**
     * installPhantomJS is the main function of the install script.
     *
     * It installs PhantomJs into the defined /bin folder,
     * taking operating system dependend archives into account.
     *
     * You need to invoke it from the scripts section of your
     * "composer.json" file as "post-install-cmd" or "post-update-cmd".
     *
     * @param Event $event
     */
    public static function installPhantomJS(Event $event)
    {
        $installer = new static($event->getComposer(), $event->getIO());
        return $installer->__invoke();
    }

    public function __invoke()
    {
        $version = $this->getVersion();

        $config = $this->getComposer()->getConfig();

        $binDir = $config->get('bin-dir');

        // the installation folder depends on the vendor-dir (default prefix is './vendor')
        $targetDir = $config->get('vendor-dir') . static::PHANTOMJS_TARGETDIR;

        $io = $this->getIO();

        // do not install a lower or equal version
        $phantomJsBinary = $this->getPhantomJsBinary($binDir);
        if ($phantomJsBinary) {
            $installedVersion = $this->getPhantomJsVersionFromBinary($phantomJsBinary);
            if (version_compare($version, $installedVersion) !== 1) {
                $io->write('   - PhantomJS v' . $installedVersion . ' is already installed. Skipping the installation.');
                return;
            }
        }

        // Download the Archive
        if ($this->download($targetDir, $version)) {
            // Copy only the PhantomJS binary from the installation "target dir" to the "bin" folder

            $this->copyPhantomJsBinaryToBinFolder($targetDir, $binDir);
        }
    }

    /**
     * Get PhantomJS application version. Equals running "phantomjs -v" on the CLI.
     *
     * @param string $pathToBinary
     * @return string PhantomJS Version
     */
    public function getPhantomJsVersionFromBinary($pathToBinary)
    {
        $io = $this->getIO();

        try {
            $cmd = escapeshellarg($pathToBinary) . ' -v';
            exec($cmd, $stdout);
            $version = $stdout[0];
            return $version;
        } catch (\Exception $e) {
            $io->warning("Caught exception while checking PhantomJS version:\n" . $e->getMessage());
            $io->notice('Re-downloading PhantomJS');
            return false;
        }
    }

    /**
     * Get path to PhantomJS binary.
     *
     * @param string $binDir
     * @return string|bool Returns false, if file not found, else filepath.
     */
    public function getPhantomJsBinary($binDir)
    {
        $os = $this->getOS();

        $binary = $binDir . '/phantomjs';

        if ($os === 'windows') {
            // the suffix for binaries on windows is ".exe"
            $binary .= '.exe';
        }

        return realpath($binary);
    }

    /**
     * The main download function.
     *
     * The package to download is created on the fly.
     * For downloading Composer\DownloadManager is used.
     * Downloads are automatically retried with a lower version number,
     * when the resource it not found (404).
     *
     * @param string $targetDir
     * @param string $version
     * @return boolean
     */
    public function download($targetDir, $version)
    {
        $io = $this->getIO();
        $downloadManager = $this->getComposer()->getDownloadManager();
        $retries = count($this->getPhantomJsVersions());

        while ($retries--) {
            $package = $this->createComposerInMemoryPackage($targetDir, $version);

            try {
                $downloadManager->download($package, $targetDir, false);
                return true;
            } catch (TransportException $e) {
                if ($e->getStatusCode() === 404) {
                    $version = $this->getLowerVersion($version);
                    $io->warning('Retrying the download with a lower version number: "' . $version . '"');
                } else {
                    $message = $e->getMessage();
                    $code = $e->getStatusCode();
                    $io->error(PHP_EOL . '<error>TransportException: "' . $message . '". HTTP status code: ' . $code . '</error>');
                    return false;
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $io->error(PHP_EOL . '<error>While downloading version ' . $version . ' the following error accoured: ' . $message . '</error>');
                return false;
            }
        }
    }

    /**
     * Returns a Composer Package, which was created in memory.
     *
     * @param string $targetDir
     * @param string $version
     * @return Package
     */
    public function createComposerInMemoryPackage($targetDir, $version)
    {
        $url = $this->getURL($version);

        $versionParser = new VersionParser();
        $normVersion = $versionParser->normalize($version);

        $package = new Package(static::PHANTOMJS_NAME, $normVersion, $version);
        $package->setTargetDir($targetDir);
        $package->setInstallationSource('dist');
        $package->setDistType(pathinfo($url, PATHINFO_EXTENSION) === 'zip' ? 'zip' : 'tar'); // set zip, tarball
        $package->setDistUrl($url);

        return $package;
    }

    /**
     * Returns an array with PhantomJs version numbers.
     *
     * @return array PhantomJs version numbers
     */
    public function getPhantomJsVersions()
    {
        return array('2.1.1', '2.0.0', '1.9.8', '1.9.7');
    }

    /**
     * Returns the latest PhantomJsVersion.
     *
     * @return string Latest PhantomJs Version.
     */
    public function getLatestPhantomJsVersion()
    {
        $versions = $this->getPhantomJsVersions();

        return $versions[0];
    }

    /**
     * Returns a lower version for a version number.
     *
     * @param string $old_version Version number
     * @return string Lower version number.
     */
    public function getLowerVersion($old_version)
    {
        foreach ($this->getPhantomJsVersions() as $idx => $version) {
            // if $old_version is bigger than $version from versions array, return $version
            if (version_compare($old_version, $version) == 1) {
                return $version;
            }
        }
    }

    /**
     * Returns the PhantomJS version number.
     *
     * Firstly, we search for a version number in the local repository,
     * secondly, in the root package.
     * A version specification of "dev-master#<commit-reference>" is disallowed.
     *
     * @return string $version Version
     */
    public function getVersion()
    {
        $composer = $this->getComposer();

        // try getting the version from the local repository
        $packages = $composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
        foreach ($packages as $package) {
            if ($package->getName() === static::PACKAGE_NAME) {
                $version = $package->getPrettyVersion();
                break;
            }
        }

        // let's take a look at the aliases
        $aliases = $composer->getLocker()->getAliases();
        foreach ($aliases as $idx => $alias) {
            if ($alias['package'] === static::PACKAGE_NAME) {
                return $alias['alias'];
            }
        }

        // fallback to the hardcoded latest version, if "dev-master" was set
        if ($version === 'dev-master') {
            return $this->getLatestPhantomJsVersion();
        }

        // grab version from commit-reference, e.g. "dev-master#<commit-ref> as version"
        if (preg_match('/dev-master#(?:.*)(\d.\d.\d)/i', $version, $matches)) {
            return $matches[1];
        }

        // grab version from a Composer patch version tag with a patch level, like "1.9.8-p02"
        if (preg_match('/(\d.\d.\d)(?:(?:-p\d{2})?)/i', $version, $matches)) {
            return $matches[1];
        }

        // let's take a look at the root package
        if (!empty($version)) {
            $version = $this->getRequiredVersion($composer->getPackage());
        }

        return $version;
    }

    /**
     * Returns the version for the given package either from the "require" or "require-dev" packages array.
     *
     * @param RootPackageInterface $package
     * @throws \RuntimeException
     * @return mixed
     */
    public function getRequiredVersion(RootPackageInterface $package)
    {
        foreach (array($package->getRequires(), $package->getDevRequires()) as $requiredPackages) {
            if (isset($requiredPackages[static::PACKAGE_NAME])) {
                return $requiredPackages[static::PACKAGE_NAME]->getPrettyConstraint();
            }
        }
        throw new \RuntimeException('Can not determine required version of ' . static::PACKAGE_NAME);
    }

    /**
     * Copies the PhantomJs binary to the bin folder.
     * Takes different "folder structure" of the archives and different "binary file names" into account.
     *
     * @param  string $targetDir path to /vendor/jakoch/phantomjs
     * @param  string $binDir path to binary folder
     *
     * @return bool True, if file dropped. False, otherwise.
     */
    public function copyPhantomJsBinaryToBinFolder($targetDir, $binDir)
    {
        if (!is_dir($binDir)) {
            mkdir($binDir);
        }

        $os = $this->getOS();

        $sourceName = '/bin/phantomjs';
        $targetName = $binDir . '/phantomjs';

        if ($os === 'windows') {
            // the suffix for binaries on windows is ".exe"
            $sourceName .= '.exe';
            $targetName .= '.exe';

            /**
             * The release folder structure changed between versions.
             * For versions up to v1.9.8, the executables resides at the root.
             * From v2.0.0 on, the executable resides in the bin folder.
             */
            if (is_file($targetDir . '/phantomjs.exe')) {
                $sourceName = str_replace('/bin', '', $sourceName);
            }

            // slash fix (not needed, but looks better on the dropped php file)
            $targetName = str_replace('/', '\\', $targetName);
        }

        if ($os !== 'unknown') {
            copy($targetDir . $sourceName, $targetName);
            chmod($targetName, static::PHANTOMJS_CHMODE);
        }

        $this->dropClassWithPathToInstalledBinary($targetName);
    }

    /**
     * Drop php class with path to installed phantomjs binary for easier usage.
     *
     * Usage:
     *
     * use PhantomInstaller\PhantomBinary;
     *
     * $bin = PhantomInstaller\PhantomBinary::BIN;
     * $dir = PhantomInstaller\PhantomBinary::DIR;
     *
     * $bin = PhantomInstaller\PhantomBinary::getBin();
     * $dir = PhantomInstaller\PhantomBinary::getDir();
     *
     * @param  string $binaryPath full path to binary
     *
     * @return bool True, if file dropped. False, otherwise.
     */
    public function dropClassWithPathToInstalledBinary($binaryPath)
    {
        $code = "<?php\n";
        $code .= "\n";
        $code .= "namespace PhantomInstaller;\n";
        $code .= "\n";
        $code .= "class PhantomBinary\n";
        $code .= "{\n";
        $code .= "    const BIN = '%binary%';\n";
        $code .= "    const DIR = '%binary_dir%';\n";
        $code .= "\n";
        $code .= "    public static function getBin() {\n";
        $code .= "        return self::BIN;\n";
        $code .= "    }\n";
        $code .= "\n";
        $code .= "    public static function getDir() {\n";
        $code .= "        return self::DIR;\n";
        $code .= "    }\n";
        $code .= "}\n";

        // binary      = full path to the binary
        // binary_dir  = the folder the binary resides in
        $fileContent = str_replace(
            array('%binary%', '%binary_dir%'),
            array($binaryPath, dirname($binaryPath)),
            $code
        );

        return (bool)file_put_contents(__DIR__ . '/PhantomBinary.php', $fileContent);
    }

    /**
     * Returns the URL of the PhantomJS distribution for the installing OS.
     *
     * @param string $version
     * @return string Download URL
     */
    public function getURL($version)
    {
        $file = false;
        $os = $this->getOS();
        $cdn_url = $this->getCdnUrl($version);

        if ($os === 'windows') {
            $file = 'phantomjs-' . $version . '-windows.zip';
        }

        if ($os === 'linux') {
            $bitsize = $this->getBitSize();

            if ($bitsize === '32') {
                $file = 'phantomjs-' . $version . '-linux-i686.tar.bz2';
            }

            if ($bitsize === '64') {
                $file = 'phantomjs-' . $version . '-linux-x86_64.tar.bz2';
            }
        }

        if ($os === 'macosx') {
            $file = 'phantomjs-' . $version . '-macosx.zip';
        }

        # OS unknown
        if ($file === false) {
            throw new \RuntimeException(
                'The Installer could not select a PhantomJS package for this OS.
                Please install PhantomJS manually into the /bin folder of your project.'
            );
        }

        return $cdn_url . $file;
    }

    /**
     * Default CDN URL
     */
    const PHANTOMJS_CDNURL_DEFAULT = 'https://api.bitbucket.org/2.0/repositories/ariya/phantomjs/downloads/';

    /**
     * Returns the base URL for downloads.
     *
     * Checks (order by highest precedence on top):
     * - ENV var "PHANTOMJS_CDNURL"
     * - SERVER var "PHANTOMJS_CDNURL"
     * - $['extra']['jakoch/phantomjs-installer']['cdnurl'] in composer.json
     * - default location (bitbucket)
     *
     * == Official Downloads
     *
     * The old versions up to v1.9.2 were hosted on https://phantomjs.googlecode.com/files/
     * Newer versions are hosted on https://bitbucket.org/ariya/phantomjs/downloads/
     * Downloads via APIv2: https://api.bitbucket.org/2.0/repositories/ariya/phantomjs/downloads/
     *
     * == Mirrors
     *
     * NPM USA
     *  https://cnpmjs.org/downloads
     *  https://cnpmjs.org/mirrors/phantomjs/phantomjs-2.1.1-windows.zip
     *
     * NPM China
     *  https://npm.taobao.org/mirrors/phantomjs/
     *  https://npm.taobao.org/mirrors/phantomjs/phantomjs-2.1.1-windows.zip
     *
     * Github, USA, SF
     *  https://github.com/Medium/phantomjs/
     *  https://github.com/Medium/phantomjs/releases/download/v2.1.1/phantomjs-2.1.1-windows.zip
     *
     * @param string $version
     *
     * @return string URL
     */
    public function getCdnUrl($version)
    {
        $url = '';
        $extraData = $this->getComposer()->getPackage()->getExtra();

        // override the detection of the default URL
        // by checking for an env var and returning early
        if (isset($_ENV['PHANTOMJS_CDNURL'])) {
            $url = $_ENV['PHANTOMJS_CDNURL'];
        } elseif (isset($_SERVER['PHANTOMJS_CDNURL'])) {
            $url = $_SERVER['PHANTOMJS_CDNURL'];
        } elseif (isset($extraData[static::PACKAGE_NAME]['cdnurl'])) {
            $url = $extraData[static::PACKAGE_NAME]['cdnurl'];
        }

        if ($url == '') {
            $url = static::PHANTOMJS_CDNURL_DEFAULT;
        }

        // add slash at the end of the URL, if missing
        if ($url[strlen($url) - 1] != '/') {
            $url .= '/';
        }

        // add version to URL when using "github.com/medium/phantomjs"
        if (substr(strtolower($url), -28) == 'github.com/medium/phantomjs/') {
            $url .= 'releases/download/v' . $version . '/';
        }

        return $url;
    }

    /**
     * Returns the Operating System.
     *
     * @return string OS, e.g. macosx, windows, linux.
     */
    public function getOS()
    {
        // override the detection of the operation system
        // by checking for an env var and returning early
        if (isset($_ENV['PHANTOMJS_PLATFORM'])) {
            return strtolower($_ENV['PHANTOMJS_PLATFORM']);
        }

        if (isset($_SERVER['PHANTOMJS_PLATFORM'])) {
            return strtolower($_SERVER['PHANTOMJS_PLATFORM']);
        }

        $uname = strtolower(php_uname());

        if (strpos($uname, 'darwin') !== false ||
            strpos($uname, 'openbsd') !== false ||
            strpos($uname, 'freebsd') !== false
        ) {
            return 'macosx';
        } elseif (strpos($uname, 'win') !== false) {
            return 'windows';
        } elseif (strpos($uname, 'linux') !== false) {
            return 'linux';
        } else {
            return 'unknown';
        }
    }

    /**
     * Returns the Bit-Size.
     *
     * @return string BitSize, e.g. 32, 64.
     */
    public function getBitSize()
    {
        // override the detection of the bitsize
        // by checking for an env var and returning early
        if (isset($_ENV['PHANTOMJS_BITSIZE'])) {
            return strtolower($_ENV['PHANTOMJS_BITSIZE']);
        }

        if (isset($_SERVER['PHANTOMJS_BITSIZE'])) {
            return strtolower($_SERVER['PHANTOMJS_BITSIZE']);
        }

        if (PHP_INT_SIZE === 4) {
            return '32';
        }

        if (PHP_INT_SIZE === 8) {
            return '64';
        }

        return (string)PHP_INT_SIZE; // 16-bit?
    }
}
