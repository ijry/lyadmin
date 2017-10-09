<?php

require_once __DIR__.'/../vendor/autoload.php';

use GitElephant\Repository;
use GitElephant\Objects\Branch;
use GitElephant\Objects\Commit;
use GitElephant\Objects\Tag;

$repo = Repository::open(realpath(__DIR__.'/../'));
$binaryFile = $repo->getTree('HEAD', 'src/GitElephant/GitBinary.php');

$master = new Branch($repo, 'master'); // pick a branch
$commit = Commit::pick($repo, '83e26d0f'); // pick a single commit
$v1 = Tag::pick($repo, 'v0.1.0');

echo $repo->outputRawContent($binaryFile->getBlob(), $master);
echo $repo->outputRawContent($binaryFile->getBlob(), $commit);
echo $repo->outputRawContent($binaryFile->getBlob(), $v1);