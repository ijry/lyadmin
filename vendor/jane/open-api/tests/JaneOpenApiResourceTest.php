<?php

namespace Joli\Jane\OpenApi\Tests;

use Joli\Jane\OpenApi\Command\GenerateCommand;
use Joli\Jane\OpenApi\JaneOpenApi;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JaneOpenApiResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider resourceProvider
     */
    public function testRessources(SplFileInfo $testDirectory)
    {
        // 1. Cleanup generated
        $filesystem = new Filesystem();

        if ($filesystem->exists($testDirectory->getRealPath() . DIRECTORY_SEPARATOR . 'generated')) {
            $filesystem->remove($testDirectory->getRealPath() . DIRECTORY_SEPARATOR . 'generated');
        }

        $filesystem->mkdir($testDirectory->getRealPath() . DIRECTORY_SEPARATOR . 'generated');

        // 2. Generate
        $command = new GenerateCommand();
        $input = new ArrayInput(['--config-file' => $testDirectory->getRealPath() . DIRECTORY_SEPARATOR . '.jane-openapi'], $command->getDefinition());
        $command->execute($input, new NullOutput());

        // 3. Compare
        $expectedFinder = new Finder();
        $expectedFinder->in($testDirectory->getRealPath() . DIRECTORY_SEPARATOR . 'expected');

        $generatedFinder = new Finder();
        $generatedFinder->in($testDirectory->getRealPath() . DIRECTORY_SEPARATOR . 'generated');

        $generatedData = [];

        $this->assertEquals(count($expectedFinder), count($generatedFinder));

        foreach ($generatedFinder as $generatedFile) {
            $generatedData[$generatedFile->getRelativePathname()] = $generatedFile->getRealPath();
        }

        foreach ($expectedFinder as $expectedFile) {
            $this->assertArrayHasKey($expectedFile->getRelativePathname(), $generatedData);

            if ($expectedFile->isFile()) {
                $expectedPath = $expectedFile->getRealPath();
                $actualPath   = $generatedData[ $expectedFile->getRelativePathname() ];

                $this->assertEquals(
                    file_get_contents($expectedPath),
                    file_get_contents($actualPath),
                    "Expected " . $expectedPath . " got " . $actualPath
                );
            }
        }
    }

    public function resourceProvider()
    {
        $finder = new Finder();
        $finder->directories()->in(__DIR__.'/fixtures');
        $finder->depth('< 1');

        $data = array();

        foreach ($finder as $directory) {
            $data[] = [$directory];
        }

        return $data;
    }
}
