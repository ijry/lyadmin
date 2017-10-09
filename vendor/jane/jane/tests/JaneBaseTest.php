<?php

namespace Joli\Jane\tests;

use Joli\Jane\Command\GenerateCommand;
use Joli\Jane\Jane;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JaneBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider schemaProvider
     */
    public function testRessources(SplFileInfo $testDirectory)
    {
        // 1. Cleanup generated
        $filesystem = new Filesystem();

        if ($filesystem->exists($testDirectory->getRealPath().DIRECTORY_SEPARATOR.'generated')) {
            $filesystem->remove($testDirectory->getRealPath().DIRECTORY_SEPARATOR.'generated');
        }

        $filesystem->mkdir($testDirectory->getRealPath().DIRECTORY_SEPARATOR.'generated');

        // 2. Generate
        $command = new GenerateCommand();
        $inputArray = new ArrayInput([
            '--config-file' => $testDirectory->getRealPath().DIRECTORY_SEPARATOR.'.jane',
        ], $command->getDefinition());

        $command->execute($inputArray, new NullOutput());

        // 3. Compare
        $expectedFinder = new Finder();
        $expectedFinder->in($testDirectory->getRealPath().DIRECTORY_SEPARATOR.'expected');
        $generatedFinder = new Finder();
        $generatedFinder->in($testDirectory->getRealPath().DIRECTORY_SEPARATOR.'generated');
        $generatedData = [];

        $this->assertEquals(count($expectedFinder), count($generatedFinder), sprintf('No same number of files for %s', $testDirectory->getRelativePathname()));

        foreach ($generatedFinder as $generatedFile) {
            $generatedData[$generatedFile->getRelativePathname()] = $generatedFile->getRealPath();
        }

        foreach ($expectedFinder as $expectedFile) {
            $this->assertArrayHasKey(
                $expectedFile->getRelativePathname(),
                $generatedData,
                sprintf('File %s does not exist for %s', $expectedFile->getRelativePathname(), $testDirectory->getRelativePathname())
            );

            if ($expectedFile->isFile()) {
                $this->assertEquals(
                    file_get_contents($expectedFile->getRealPath()),
                    file_get_contents($generatedData[$expectedFile->getRelativePathname()]),
                    sprintf('File %s does not have the same content for %s', $expectedFile->getRelativePathname(), $testDirectory->getRelativePathname())
                );
            }
        }
    }

    public function schemaProvider()
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
