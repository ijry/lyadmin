![GitElephant](https://raw.github.com/matteosister/GitElephant/develop/graphics/gitelephant_600.png)

[![Latest Stable Version](https://poser.pugx.org/cypresslab/GitElephant/v/stable.png)](https://packagist.org/packages/cypresslab/GitElephant) [![License](https://poser.pugx.org/cypresslab/gitelephant/license.png)](https://packagist.org/packages/cypresslab/gitelephant) [![Total Downloads](https://poser.pugx.org/cypresslab/GitElephant/downloads.png)](https://packagist.org/packages/cypresslab/GitElephant) [![Montly Downloads](https://poser.pugx.org/cypresslab/gitelephant/d/monthly.png)](https://packagist.org/packages/cypresslab/gitelephant)

[![Build Status](https://travis-ci.org/matteosister/GitElephant.png?branch=master)](https://travis-ci.org/matteosister/GitElephant) [![Dependency Status](https://www.versioneye.com/user/projects/53da38094b3ac86052000019/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53da38094b3ac86052000019) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/matteosister/GitElephant/badges/quality-score.png?s=c7ca8a7c5ea9c64b291f6bcaef27955ed6d8a836)](https://scrutinizer-ci.com/g/matteosister/GitElephant/) [![Code Coverage](https://scrutinizer-ci.com/g/matteosister/GitElephant/badges/coverage.png?s=fd7981a4f57fd639912d1a415e3dd92615ddce51)](https://scrutinizer-ci.com/g/matteosister/GitElephant/) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/d6da541e-d928-4f70-868a-dd0b6426a7b5/mini.png)](https://insight.sensiolabs.com/projects/d6da541e-d928-4f70-868a-dd0b6426a7b5)

GitElephant is an abstraction layer to manage your git repositories with php

This library officially supports git >= 1.8, older version are supported as well, but with some caveat.

Watch a [live example](http://gitelephant.cypresslab.net) of what you can do with GitElephant, Symfony2 and a git repository...

How it works
------------

GitElephant mostly rely on the git binary to retrieve information about the repository, read the output and create an OOP layer to interact with

Some parts are (or will be) implemented by reading directly inside the .git folder

The api is completely transparent to the end user. You don't have to worry about which method is used.

Requirements
------------

- php >= 5.3.0
- *nix system with git installed

I work on linux, but the lib should work well with every unix system, as far as a git binary is available.
I don't have a windows installation to test...if someone want to help...

Installation
------------

**composer**

To install GitElephant with composer you simply need to create a *composer.json* in your project root and add:

``` json
{
    "require": {
        "cypresslab/gitelephant": "~1.0"
    }
}
```

Then run

``` bash
$ curl -s https://getcomposer.org/installer | php
$ composer install
```

You have now GitElephant installed in *vendor/cypresslab/gitelephant*

And an handy autoload file to include in you project in *vendor/autoload.php*

How to use
----------

``` php
use GitElephant\Repository;
$repo = new Repository('/path/to/git/repository');
// or the factory method
$repo = Repository::open('/path/to/git/repository');
```

By default GitElephant try to use the git binary on your system.

the *Repository* class is the main class where you can find every method you need...

**Read repository**

``` php
// get the current status
$repo->getStatusOutput(); // returns an array of lines of the status message
```

*branches*

``` php
$repo->getBranches(); // return an array of Branch objects
$repo->getMainBranch(); // return the Branch instance of the current checked out branch
$repo->getBranch('master'); // return a Branch instance by its name
$develop = Branch::checkout($repo, 'develop');
$develop = Branch::checkout($repo, 'develop', true); // create and checkout
```

*tags*

``` php
$repo->getTags(); // array of Tag instances
$repo->getTag('v1.0'); // a Tag instance by name
Tag::pick($repo, 'v1.0'); // a Tag instance by name

// last tag by date
$repo->getLastTag();
```

*commits*

``` php
$repo->getCommit(); // get a Commit instance of the current HEAD
$repo->getCommit('v1.0'); // get a Commit instance for a tag
$repo->getCommit('1ac370d'); // full sha or part of it
// or directly create a commit object
$commit = new Commit($repo, '1ac370d');
$commit = new Commit($repo, '1ac370d'); // head commit

// count commits
$repo->countCommits('1ac370d'); // number of commits to arrive at 1ac370d
// commit is countable, so, with a commit object, you can do
$commit->count();
// as well as
count($commit);
```

*remotes*

``` php
$repo->getRemote('origin'); // a Remote object
$repo->getRemotes(); // array of Remote objects

// Log contains a collection of commit objects
// syntax: getLog(<tree-ish>, path = null, limit = 15, offset = null)
$log = $repo->getLog();
$log = $repo->getLog('master', null, 5);
$log = $repo->getLog('v0.1', null, 5, 10);
// or directly create a log object
$log = new Log($repo);
$log = new Log($repo, 'v0.1', null, 5, 10);

// countable
$log->count();
count($log);

// iterable
foreach ($log as $commit) {
    echo $commit->getMessage();
}
```

*status*

If you build a GitElephant\Status\Status class, you will get a nice api for getting the actual state of the working tree and staging area.

``` php
$status = $repo->getStatus();
$status = GitElephant\Status\Status::get($repo); // it's the same...

$status->all(); // A PhpCollection of StatusFile objects
$status->untracked();
$status->modified();
$status->added();
$status->deleted();
$status->renamed();
$status->copied();
```

all this methods returns a [PhpCollection](https://github.com/schmittjoh/php-collection) of StatusFile objects

a StatusFile instance has all the information about the tree node changes. File names (and new file names for renamed objects), index and working tree status, and also a "git style" description like: *added to index* or *deleted in work tree*

**Manage repository**

You could also use GitElephant to manage your git repositories via PHP.

Your web server user (like www-data) needs to have access to the folder of the git repository

``` php
$repo->init(); // init
$repo->cloneFrom("git://github.com/matteosister/GitElephant.git"); // clone

// stage changes
$repo->stage('file1.php');
$repo->stage(); // stage all

// commit
$repo->commit('my first commit');
$repo->commit('my first commit', true); // commit and stage every pending changes in the working tree

// remotes
$repo->addRemote('awesome', 'git://github.com/matteosister/GitElephant.git');

// checkout
$repo->checkout($repo->getTag('v1.0')); // checkout a tag
$repo->checkout('master'); // checkout master

// manage branches
$repo->createBranch('develop'); // create a develop branch from current checked out branch
$repo->createBranch('develop', 'master'); // create a develop branch from master
$repo->deleteBranch('develop'); // delete the develop branch
$repo->checkoutAllRemoteBranches('origin'); // checkout all the branches from the remote repository

// manage tags
// create  a tag named v1.0 from master with the given tag message
$repo->createTag('v1.0', 'master', 'my first release!');
// create  a tag named v1.0 from the current checked out branch with the given tag message
$repo->createTag('v1.0', null, 'my first release!');
// create a tag from a Commit object
$repo->createTag($repo->getCommit());
```

**Remote repositories**

If you need to access remote repository you have to install the [ssh2 extension](http://www.php.net/manual/en/book.ssh2.php) and pass a new *Caller* to the repository. *this is a new feature...consider this in a testing phase*

``` php
$repo = new Repository('/path/to/git/repository');
$connection = ssh_connect('host', 'port');
// authorize the connection with the method you want
ssh2_auth_password($connection, 'user', 'password');
$caller = new CallerSSH2($connection, '/path/to/git/binary/on/server');
$repo = Repository::open('/path/to/git/repository');
$repo->setCaller($caller);
```

A versioned tree of files
-------------------------

A git repository is a tree structure versioned in time. So if you need to represent a repository in a, let's say, web browser, you will need
a tree representation of the repository, at a given point in history.

**Tree class**

``` php
$tree = $repo->getTree(); // retrieve the actual *HEAD* tree
$tree = $repo->getTree($repo->getCommit('1ac370d')); // retrieve a tree for a given commit
$tree = $repo->getTree('master', 'lib/vendor'); // retrieve a tree for a given path
// generate a tree
$tree = new Tree($repo);
```

The Tree class implements *ArrayAccess*, *Countable* and *Iterator* interfaces.

You can use it as an array of git objects.

``` php
foreach ($tree as $treeObject) {
    echo $treeObject;
}
```

A Object instance is a php representation of a node in a git tree

``` php
echo $treeObject; // the name of the object (folder, file or link)
$treeObject->getType(); // one class constant of Object::TYPE_BLOB, Object::TYPE_TREE and Object::TYPE_LINK
$treeObject->getSha();
$treeObject->getSize();
$treeObject->getName();
$treeObject->getSize();
$treeObject->getPath();
```

You can also pass a tree object to the repository to get its subtree

``` php
$subtree = $repo->getTree('master', $treeObject);
```

Diffs
-----

If you want to check a Diff between two commits the Diff class comes in

``` php
// get the diff between the given commit and it parent
$diff = $repo->getDiff($repo->getCommit());
// get the diff between two commits
$diff = $repo->getDiff($repo->getCommit('1ac370d'), $repo->getCommit('8fb7281'));
// same as before for a given path
$diff = $repo->getDiff($repo->getCommit('1ac370d'), $repo->getCommit('8fb7281'), 'lib/vendor');
// or even pass a Object
$diff = $repo->getDiff($repo->getCommit('1ac370d'), $repo->getCommit('8fb7281'), $treeObject);
// alternatively you could directly use the sha of the commit
$diff = $repo->getDiff('1ac370d', '8fb7281');
// manually generate a Diff object
$diff = Diff::create($repo); // defaults to the last commit
// or as explained before
$diff = Diff::create($repo, '1ac370d', '8fb7281');
```

The Diff class implements *ArrayAccess*, *Countable* and *Iterator* interfaces

You can iterate over DiffObject

``` php
foreach ($diff as $diffObject) {
    // mode is a constant of the DiffObject class
    // DiffObject::MODE_INDEX an index change
    // DiffObject::MODE_MODE a mode change
    // DiffObject::MODE_NEW_FILE a new file change
    // DiffObject::MODE_DELETED_FILE a deleted file change
    echo $diffObject->getMode();
}
```

A DiffObject is a class that implements *ArrayAccess*, *Countable* and *Iterator* interfaces. It represent a file, folder or submodule changed in the Diff.

Every DiffObject can have multiple chunks of changes. For example:

```
    added 3 lines at line 20
    deleted 4 lines at line 560
```

You can iterate over DiffObject to get DiffChunks. DiffChunks are the last steps of the Diff process, they are a collection of DiffChunkLine Objects

``` php
foreach ($diffObject as $diffChunk) {
    if (count($diffChunk) > 0) {
        echo "change detected from line ".$diffChunk->getDestStartLine()." to ".$diffChunk->getDestEndLine();
        foreach ($diffChunk as $diffChunkLine) {
            echo $diffChunkLine; // output the line content
        }
    }
}
```

Testing
-------

The library is fully tested with PHPUnit.

Go to the base library folder and install the dev dependencies with composer, and then run the phpunitt test suite

``` bash
$ composer --dev install
$ ./vendor/bin/phpunit # phpunit test suite
```

If you want to run the test suite you should have all the dependencies loaded.

Symfony2
--------

There is a [GitElephantBundle](https://github.com/matteosister/GitElephantBundle) to use this library inside a Symfony2 project.

Dependencies
------------

- [symfony/process](https://packagist.org/packages/symfony/process)
- [symfony/filesystem](https://packagist.org/packages/symfony/filesystem)
- [symfony/finder](https://packagist.org/packages/symfony/finder)
- [phpcollection/phpcollection](https://github.com/schmittjoh/php-collection)

*for tests*

- [PHPUnit](https://github.com/sebastianbergmann/phpunit)

Code style
----------

* GitElephant follows the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* I'm using [gitflow](https://github.com/nvie/gitflow)

Want to contribute?
-------------------

*You are my new hero!*

Just remember:

* PSR2 coding standard
* test everything you develop with phpunit
* if you don't use gitflow, just remember to branch from "develop" and send your PR there. **Please do not send pull requests on the master branch**.

Author
------

Matteo Giachino ([twitter](https://twitter.com/spicy_sake))

Many thanks to all the [contributors](https://github.com/matteosister/GitElephant/graphs/contributors)

Thanks
------

Many thanks to Linus and all those who have worked/contributed in any way to git.
Because **it's awesome!!!** I can't imagine being a developer without it.

Logo design by [Stefano Lodovico](http://it.linkedin.com/pub/stefano-lodovico/49/a04/261)

[![Analytics](https://ga-beacon.appspot.com/UA-33181125-2/GitElephant/README?pixel)](https://github.com/igrigorik/ga-beacon)
