PHPGit - A Git wrapper for PHP5.3+
==================================

[![Latest Unstable Version](https://poser.pugx.org/kzykhys/git/v/unstable.png)](https://packagist.org/packages/kzykhys/git)
[![Build Status](https://travis-ci.org/kzykhys/PHPGit.png?branch=master)](https://travis-ci.org/kzykhys/PHPGit)
[![Coverage Status](https://coveralls.io/repos/kzykhys/PHPGit/badge.png)](https://coveralls.io/r/kzykhys/PHPGit)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/04f10b57-a113-47ad-8dda-9a6dacbb079f/mini.png)](https://insight.sensiolabs.com/projects/04f10b57-a113-47ad-8dda-9a6dacbb079f)

Requirements
------------

* PHP5.3
* Git

Installation
------------

Update your composer.json and run `composer update`

``` json
{
    "require": {
        "kzykhys/git": "dev-master"
    }
}
```

Basic Usage
-----------

``` php
<?php

require __DIR__ . '/vendor/autoload.php';

$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
$git->setRepository('/path/to/repo');
$git->remote->add('production', 'git://example.com/your/repo.git');
$git->add('README.md');
$git->commit('Adds README.md');
$git->checkout('release');
$git->merge('master');
$git->push();
$git->push('production', 'release');
$git->tag->create('v1.0.1', 'release');

foreach ($git->tree('release') as $object) {
    if ($object['type'] == 'blob') {
        echo $git->show($object['file']);
    }
}
```

API
---

### Git commands

* [git add](#git-add)
    * $git->[add](#git-addstringarraytraversable-file-array-options--)(_string|array|\Traversable_ $file, _array_ $options = [])
* [git archive](#git-archive)
    * $git->[archive](#git-archivestring-file-string-tree--null-stringarraytraversable-path--null-array-options--)(_string_ $file, _string_ $tree = null, _string|array|\Traversable_ $path = null, _array_ $options = [])
* [git branch](#git-branch)
    * $git->[branch](#git-brancharray-options--)(_array_ $options = [])
    * $git->branch->[create](#git-branch-createstring-branch-string-startpoint--null-array-options--)(_string_ $branch, _string_ $startPoint = null, _array_ $options = [])
    * $git->branch->[move](#git-branch-movestring-branch-string-newbranch-array-options--)(_string_ $branch, _string_ $newBranch, _array_ $options = [])
    * $git->branch->[delete](#git-branch-deletestring-branch-array-options--)(_string_ $branch, _array_ $options = [])
* [git cat-file](#git-cat-file)
    * $git->cat->[blob](#git-cat-blobstring-object)(_string_ $object)
    * $git->cat->[type](#git-cat-typestring-object)(_string_ $object)
    * $git->cat->[size](#git-cat-sizestring-object)(_string_ $object)
* [git checkout](#git-checkout)
    * $git->[checkout](#git-checkoutstring-branch-array-options--)(_string_ $branch, _array_ $options = [])
    * $git->checkout->[create](#git-checkout-createstring-branch-string-startpoint--null-array-options--)(_string_ $branch, _string_ $startPoint = null, _array_ $options = [])
    * $git->checkout->[orphan](#git-checkout-orphanstring-branch-string-startpoint--null-array-options--)(_string_ $branch, _string_ $startPoint = null, _array_ $options = [])
* [git clone](#git-clone)
    * $git->[clone](#git-clonestring-repository-string-path--null-array-options--)(_string_ $repository, _string_ $path = null, _array_ $options = [])
* [git commit](#git-commit)
    * $git->[commit](#git-commitstring-message-array-options--)(_string_ $message, _array_ $options = [])
* [git config](#git-config)
    * $git->[config](#git-configarray-options--)(_array_ $options = [])
    * $git->config->[set](#git-config-setstring-name-string-value-array-options--)(_string_ $name, _string_ $value, _array_ $options = [])
    * $git->config->[add](#git-config-addstring-name-string-value-array-options--)(_string_ $name, _string_ $value, _array_ $options = [])
* [git describe](#git-describe)
    * $git->[describe](#git-describestring-committish--null-array-options--)(_string_ $committish = null, _array_ $options = [])
    * $git->describe->[tags](#git-describe-tagsstring-committish--null-array-options--)(_string_ $committish = null, _array_ $options = [])
* [git fetch](#git-fetch)
    * $git->[fetch](#git-fetchstring-repository-string-refspec--null-array-options--)(_string_ $repository, _string_ $refspec = null, _array_ $options = [])
    * $git->fetch->[all](#git-fetch-allarray-options--)(_array_ $options = [])
* [git init](#git-init)
    * $git->[init](#git-initstring-path-array-options--)(_string_ $path, _array_ $options = [])
* [git log](#git-log)
    * $git->[log](#git-logstring-revrange---string-path--null-array-options--)(_string_ $revRange = '', _string_ $path = null, _array_ $options = [])
* [git merge](#git-merge)
    * $git->[merge](#git-mergestringarraytraversable-commit-string-message--null-array-options--)(_string|array|\Traversable_ $commit, _string_ $message = null, _array_ $options = [])
    * $git->merge->[abort](#git-merge-abort)()
* [git mv](#git-mv)
    * $git->[mv](#git-mvstringarrayiterator-source-string-destination-array-options--)(_string|array|\Iterator_ $source, _string_ $destination, _array_ $options = [])
* [git pull](#git-pull)
    * $git->[pull](#git-pullstring-repository--null-string-refspec--null-array-options--)(_string_ $repository = null, _string_ $refspec = null, _array_ $options = [])
* [git push](#git-push)
    * $git->[push](#git-pushstring-repository--null-string-refspec--null-array-options--)(_string_ $repository = null, _string_ $refspec = null, _array_ $options = [])
* [git rebase](#git-rebase)
    * $git->[rebase](#git-rebasestring-upstream--null-string-branch--null-array-options--)(_string_ $upstream = null, _string_ $branch = null, _array_ $options = [])
    * $git->rebase->[continues](#git-rebase-continues)()
    * $git->rebase->[abort](#git-rebase-abort)()
    * $git->rebase->[skip](#git-rebase-skip)()
* [git remote](#git-remote)
    * $git->[remote](#git-remote)()
    * $git->remote->[add](#git-remote-addstring-name-string-url-array-options--)(_string_ $name, _string_ $url, _array_ $options = [])
    * $git->remote->[rename](#git-remote-renamestring-name-string-newname)(_string_ $name, _string_ $newName)
    * $git->remote->[rm](#git-remote-rmstring-name)(_string_ $name)
    * $git->remote->[show](#git-remote-showstring-name)(_string_ $name)
    * $git->remote->[prune](#git-remote-prunestring-name--null)(_string_ $name = null)
    * $git->remote->[head](#git-remote-headstring-name-string-branch--null)(_string_ $name, _string_ $branch = null)
    * $git->remote->head->[set](#git-remote-head-setstring-name-string-branch)(_string_ $name, _string_ $branch)
    * $git->remote->head->[delete](#git-remote-head-deletestring-name)(_string_ $name)
    * $git->remote->head->[remote](#git-remote-head-remotestring-name)(_string_ $name)
    * $git->remote->[branches](#git-remote-branchesstring-name-array-branches)(_string_ $name, _array_ $branches)
    * $git->remote->branches->[set](#git-remote-branches-setstring-name-array-branches)(_string_ $name, _array_ $branches)
    * $git->remote->branches->[add](#git-remote-branches-addstring-name-array-branches)(_string_ $name, _array_ $branches)
    * $git->remote->[url](#git-remote-urlstring-name-string-newurl-string-oldurl--null-array-options--)(_string_ $name, _string_ $newUrl, _string_ $oldUrl = null, _array_ $options = [])
    * $git->remote->url->[set](#git-remote-url-setstring-name-string-newurl-string-oldurl--null-array-options--)(_string_ $name, _string_ $newUrl, _string_ $oldUrl = null, _array_ $options = [])
    * $git->remote->url->[add](#git-remote-url-addstring-name-string-newurl-array-options--)(_string_ $name, _string_ $newUrl, _array_ $options = [])
    * $git->remote->url->[delete](#git-remote-url-deletestring-name-string-url-array-options--)(_string_ $name, _string_ $url, _array_ $options = [])
* [git reset](#git-reset)
    * $git->[reset](#git-resetstringarraytraversable-paths-string-commit--null)(_string|array|\Traversable_ $paths, _string_ $commit = null)
    * $git->reset->[soft](#git-reset-softstring-commit--null)(_string_ $commit = null)
    * $git->reset->[mixed](#git-reset-mixedstring-commit--null)(_string_ $commit = null)
    * $git->reset->[hard](#git-reset-hardstring-commit--null)(_string_ $commit = null)
    * $git->reset->[merge](#git-reset-mergestring-commit--null)(_string_ $commit = null)
    * $git->reset->[keep](#git-reset-keepstring-commit--null)(_string_ $commit = null)
    * $git->reset->[mode](#git-reset-modestring-mode-string-commit--null)(_string_ $mode, _string_ $commit = null)
* [git rm](#git-rm)
    * $git->[rm](#git-rmstringarraytraversable-file-array-options--)(_string|array|\Traversable_ $file, _array_ $options = [])
    * $git->rm->[cached](#git-rm-cachedstringarraytraversable-file-array-options--)(_string|array|\Traversable_ $file, _array_ $options = [])
* [git shortlog](#git-shortlog)
    * $git->[shortlog](#git-shortlogstringarraytraversable-commits--head)(_string|array|\Traversable_ $commits = HEAD)
    * $git->shortlog->[summary](#git-shortlog-summarystring-commits--head)(_string_ $commits = HEAD)
* [git show](#git-show)
    * $git->[show](#git-showstring-object-array-options--)(_string_ $object, _array_ $options = [])
* [git stash](#git-stash)
    * $git->[stash](#git-stash)()
    * $git->stash->[save](#git-stash-savestring-message--null-array-options--)(_string_ $message = null, _array_ $options = [])
    * $git->stash->[lists](#git-stash-listsarray-options--)(_array_ $options = [])
    * $git->stash->[show](#git-stash-showstring-stash--null)(_string_ $stash = null)
    * $git->stash->[drop](#git-stash-dropstring-stash--null)(_string_ $stash = null)
    * $git->stash->[pop](#git-stash-popstring-stash--null-array-options--)(_string_ $stash = null, _array_ $options = [])
    * $git->stash->[apply](#git-stash-applystring-stash--null-array-options--)(_string_ $stash = null, _array_ $options = [])
    * $git->stash->[branch](#git-stash-branchstring-name-string-stash--null)(_string_ $name, _string_ $stash = null)
    * $git->stash->[clear](#git-stash-clear)()
    * $git->stash->[create](#git-stash-create)()
* [git status](#git-status)
    * $git->[status](#git-statusarray-options--)(_array_ $options = [])
* [git tag](#git-tag)
    * $git->[tag](#git-tag)()
    * $git->tag->[create](#git-tag-createstring-tag-string-commit--null-array-options--)(_string_ $tag, _string_ $commit = null, _array_ $options = [])
    * $git->tag->[delete](#git-tag-deletestringarraytraversable-tag)(_string|array|\Traversable_ $tag)
    * $git->tag->[verify](#git-tag-verifystringarraytraversable-tag)(_string|array|\Traversable_ $tag)
* [git ls-tree](#git-ls-tree)
    * $git->[tree](#git-treestring-branch--master-string-path--)(_string_ $branch = master, _string_ $path = '')

* * * * *

### git add

#### $git->add(_string|array|\Traversable_ $file, _array_ $options = [])

Add file contents to the index

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->add('file.txt');
$git->add('file.txt', ['force' => false, 'ignore-errors' => false);
```

##### Options

- **force**          (_boolean_) Allow adding otherwise ignored files
- **ignore-errors**  (_boolean_) Do not abort the operation

* * * * *

### git archive

#### $git->archive(_string_ $file, _string_ $tree = null, _string|array|\Traversable_ $path = null, _array_ $options = [])

Create an archive of files from a named tree

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->archive('repo.zip', 'master', null, ['format' => 'zip']);
```

##### Options

- **format** (_boolean_) Format of the resulting archive: tar or zip
- **prefix** (_boolean_) Prepend prefix/ to each filename in the archive

* * * * *

### git branch

#### $git->branch(_array_ $options = [])

Returns an array of both remote-tracking branches and local branches

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$branches = $git->branch();
```

##### Output Example

```
[
    'master' => ['current' => true, 'name' => 'master', 'hash' => 'bf231bb', 'title' => 'Initial Commit'],
    'origin/master' => ['current' => false, 'name' => 'origin/master', 'alias' => 'remotes/origin/master']
]
```

##### Options

- **all**     (_boolean_) List both remote-tracking branches and local branches
- **remotes** (_boolean_) List the remote-tracking branches

#### $git->branch->create(_string_ $branch, _string_ $startPoint = null, _array_ $options = [])

Creates a new branch head named **$branch** which points to the current HEAD, or **$startPoint** if given

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->branch->create('bugfix');              // from current HEAD
$git->branch->create('patch-1', 'a092bf7s'); // from commit
$git->branch->create('1.0.x-fix', 'v1.0.2'); // from tag
```

##### Options

- **force**   (_boolean_) Reset **$branch**  to **$startPoint** if **$branch** exists already

#### $git->branch->move(_string_ $branch, _string_ $newBranch, _array_ $options = [])

Move/rename a branch and the corresponding reflog

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->branch->move('bugfix', '2.0');
```

##### Options

- **force**   (_boolean_) Move/rename a branch even if the new branch name already exists

#### $git->branch->delete(_string_ $branch, _array_ $options = [])

Delete a branch

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->branch->delete('2.0');
```

The branch must be fully merged in its upstream branch, or in HEAD if no upstream was set with --track or --set-upstream.

##### Options

- **force**   (_boolean_) Delete a branch irrespective of its merged status

* * * * *

### git cat-file

#### $git->cat->blob(_string_ $object)

Returns the contents of blob object

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$contents = $git->cat->blob('e69de29bb2d1d6434b8b29ae775ad8');
```

#### $git->cat->type(_string_ $object)

Returns the object type identified by **$object**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$type = $git->cat->type('e69de29bb2d1d6434b8b29ae775ad8');
```

#### $git->cat->size(_string_ $object)

Returns the object size identified by **$object**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$type = $git->cat->size('e69de29bb2d1d6434b8b29ae775ad8');
```

* * * * *

### git checkout

#### $git->checkout(_string_ $branch, _array_ $options = [])

Switches branches by updating the index, working tree, and HEAD to reflect the specified branch or commit

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->checkout('develop');
```

##### Options

- **force** (_boolean_) Proceed even if the index or the working tree differs from HEAD
- **merge** (_boolean_) Merges local modification

#### $git->checkout->create(_string_ $branch, _string_ $startPoint = null, _array_ $options = [])

Create a new branch and checkout

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->checkout->create('patch-1');
$git->checkout->create('patch-2', 'develop');
```

##### Options

- **force** (_boolean_) Proceed even if the index or the working tree differs from HEAD

#### $git->checkout->orphan(_string_ $branch, _string_ $startPoint = null, _array_ $options = [])

Create a new orphan branch, named <new_branch>, started from <start_point> and switch to it

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->checkout->orphan('gh-pages');
```

##### Options

- **force** (_boolean_) Proceed even if the index or the working tree differs from HEAD

* * * * *

### git clone

#### $git->clone(_string_ $repository, _string_ $path = null, _array_ $options = [])

Clone a repository into a new directory

``` php
$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
```

##### Options

- **shared** (_boolean_) Starts out without any object of its own
- **bare**   (_boolean_) Make a bare GIT repository

* * * * *

### git commit

#### $git->commit(_string_ $message, _array_ $options = [])

Record changes to the repository

``` php
$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
$git->setRepository('/path/to/repo');
$git->add('README.md');
$git->commit('Fixes README.md');
```

##### Options

- **all**           (_boolean_) Stage files that have been modified and deleted
- **reuse-message** (_string_)  Take an existing commit object, and reuse the log message and the authorship information (including the timestamp) when creating the commit
- **squash**        (_string_)  Construct a commit message for use with rebase --autosquash
- **author**        (_string_)  Override the commit author
- **date**          (_string_)  Override the author date used in the commit
- **cleanup**       (_string_)  Can be one of verbatim, whitespace, strip, and default
- **amend**         (_boolean_) Used to amend the tip of the current branch

* * * * *

### git config

#### $git->config(_array_ $options = [])

Returns all variables set in config file


##### Options

- **global** (_boolean_) Read or write configuration options for the current user
- **system** (_boolean_) Read or write configuration options for all users on the current machine

#### $git->config->set(_string_ $name, _string_ $value, _array_ $options = [])

Set an option

##### Options

- **global** (_boolean_) Read or write configuration options for the current user
- **system** (_boolean_) Read or write configuration options for all users on the current machine

#### $git->config->add(_string_ $name, _string_ $value, _array_ $options = [])

Adds a new line to the option without altering any existing values

##### Options

- **global** (_boolean_) Read or write configuration options for the current user
- **system** (_boolean_) Read or write configuration options for all users on the current machine

* * * * *

### git describe

#### $git->describe(_string_ $committish = null, _array_ $options = [])

Returns the most recent tag that is reachable from a commit

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->tag->create('v1.0.0');
$git->commit('Fixes #14');
echo $git->describe('HEAD', ['tags' => true]);
```

##### Output Example

```
v1.0.0-1-g7049efc
```

##### Options

- **all**    (_boolean_) Enables matching any known branch, remote-tracking branch, or lightweight tag
- **tags**   (_boolean_) Enables matching a lightweight (non-annotated) tag
- **always** (_boolean_) Show uniquely abbreviated commit object as fallback

#### $git->describe->tags(_string_ $committish = null, _array_ $options = [])

Equivalent to $git->describe($committish, ['tags' => true]);

* * * * *

### git fetch

#### $git->fetch(_string_ $repository, _string_ $refspec = null, _array_ $options = [])

Fetches named heads or tags from one or more other repositories, along with the objects necessary to complete them

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'git://your/repo.git');
$git->fetch('origin');
```

##### Options

- **append** (_boolean_) Append ref names and object names of fetched refs to the existing contents of .git/FETCH_HEAD
- **keep**   (_boolean_) Keep downloaded pack
- **prune**  (_boolean_) After fetching, remove any remote-tracking branches which no longer exist on the remote

#### $git->fetch->all(_array_ $options = [])

Fetch all remotes

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'git://your/repo.git');
$git->remote->add('release', 'git://your/another_repo.git');
$git->fetch->all();
```

##### Options

- **append** (_boolean_) Append ref names and object names of fetched refs to the existing contents of .git/FETCH_HEAD
- **keep**   (_boolean_) Keep downloaded pack
- **prune**  (_boolean_) After fetching, remove any remote-tracking branches which no longer exist on the remote

* * * * *

### git init

#### $git->init(_string_ $path, _array_ $options = [])

Create an empty git repository or reinitialize an existing one

``` php
$git = new PHPGit\Git();
$git->init('/path/to/repo1');
$git->init('/path/to/repo2', array('shared' => true, 'bare' => true));
```

##### Options

- **shared** (_boolean_) Specify that the git repository is to be shared amongst several users
- **bare**   (_boolean_) Create a bare repository

* * * * *

### git log

#### $git->log(_string_ $revRange = '', _string_ $path = null, _array_ $options = [])

Returns the commit logs

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$logs = $git->log(array('limit' => 10));
```

##### Output Example

``` php
[
    0 => [
        'hash'  => '1a821f3f8483747fd045eb1f5a31c3cc3063b02b',
        'name'  => 'John Doe',
        'email' => 'john@example.com',
        'date'  => 'Fri Jan 17 16:32:49 2014 +0900',
        'title' => 'Initial Commit'
    ],
    1 => [
        //...
    ]
]
```

##### Options

- **limit** (_integer_) Limits the number of commits to show
- **skip**  (_integer_) Skip number commits before starting to show the commit output

* * * * *

### git merge

#### $git->merge(_string|array|\Traversable_ $commit, _string_ $message = null, _array_ $options = [])

Incorporates changes from the named commits into the current branch

```php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->merge('1.0');
$git->merge('1.1', 'Merge message', ['strategy' => 'ours']);
```

##### Options

- **no-ff**               (_boolean_) Do not generate a merge commit if the merge resolved as a fast-forward, only update the branch pointer
- **rerere-autoupdate**   (_boolean_) Allow the rerere mechanism to update the index with the result of auto-conflict resolution if possible
- **squash**              (_boolean_) Allows you to create a single commit on top of the current branch whose effect is the same as merging another branch
- **strategy**            (_string_)  Use the given merge strategy
- **strategy-option**     (_string_)  Pass merge strategy specific option through to the merge strategy

#### $git->merge->abort()

Abort the merge process and try to reconstruct the pre-merge state

```php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
try {
    $git->merge('dev');
} catch (PHPGit\Exception\GitException $e) {
    $git->merge->abort();
}
```

* * * * *

### git mv

#### $git->mv(_string|array|\Iterator_ $source, _string_ $destination, _array_ $options = [])

Move or rename a file, a directory, or a symlink

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->mv('UPGRADE-1.0.md', 'UPGRADE-1.1.md');
```

##### Options

- **force** (_boolean_) Force renaming or moving of a file even if the target exists

* * * * *

### git pull

#### $git->pull(_string_ $repository = null, _string_ $refspec = null, _array_ $options = [])

Fetch from and merge with another repository or a local branch

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->pull('origin', 'master');
```

* * * * *

### git push

#### $git->push(_string_ $repository = null, _string_ $refspec = null, _array_ $options = [])

Update remote refs along with associated objects

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->push('origin', 'master');
```

* * * * *

### git rebase

#### $git->rebase(_string_ $upstream = null, _string_ $branch = null, _array_ $options = [])

Forward-port local commits to the updated upstream head

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->fetch('origin');
$git->rebase('origin/master');
```

##### Options

- **onto**          (_string_)  Starting point at which to create the new commits
- **no-verify**     (_boolean_) Bypasses the pre-rebase hook
- **force-rebase**  (_boolean_) Force the rebase even if the current branch is a descendant of the commit you are rebasing onto

#### $git->rebase->continues()

Restart the rebasing process after having resolved a merge conflict

#### $git->rebase->abort()

Abort the rebase operation and reset HEAD to the original branch

#### $git->rebase->skip()

Restart the rebasing process by skipping the current patch

* * * * *

### git remote

#### $git->remote()

Returns an array of existing remotes

``` php
$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/Text.git', '/path/to/repo');
$git->setRepository('/path/to/repo');
$remotes = $git->remote();
```

##### Output Example

``` php
[
    'origin' => [
        'fetch' => 'https://github.com/kzykhys/Text.git',
        'push'  => 'https://github.com/kzykhys/Text.git'
    ]
]
```

#### $git->remote->add(_string_ $name, _string_ $url, _array_ $options = [])

Adds a remote named **$name** for the repository at **$url**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->fetch('origin');
```

##### Options

- **tags**    (_boolean_) With this option, `git fetch <name>` imports every tag from the remote repository
- **no-tags** (_boolean_) With this option, `git fetch <name>` does not import tags from the remote repository

#### $git->remote->rename(_string_ $name, _string_ $newName)

Rename the remote named **$name** to **$newName**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->rename('origin', 'upstream');
```

#### $git->remote->rm(_string_ $name)

Remove the remote named **$name**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->rm('origin');
```

#### $git->remote->show(_string_ $name)

Gives some information about the remote **$name**

``` php
$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/Text.git', '/path/to/repo');
$git->setRepository('/path/to/repo');
echo $git->remote->show('origin');
```

##### Output Example

```
\* remote origin
  Fetch URL: https://github.com/kzykhys/Text.git
  Push  URL: https://github.com/kzykhys/Text.git
  HEAD branch: master
  Remote branch:
    master tracked
  Local branch configured for 'git pull':
    master merges with remote master
  Local ref configured for 'git push':
    master pushes to master (up to date)
```

#### $git->remote->prune(_string_ $name = null)

Deletes all stale remote-tracking branches under **$name**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->prune('origin');
```

#### $git->remote->head(_string_ $name, _string_ $branch = null)

Alias of set()

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->head('origin');
```

#### $git->remote->head->set(_string_ $name, _string_ $branch)

Sets the default branch for the named remote

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->head->set('origin');
```

#### $git->remote->head->delete(_string_ $name)

Deletes the default branch for the named remote

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->head->delete('origin');
```

#### $git->remote->head->remote(_string_ $name)

Determine the default branch by querying remote

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->head->remote('origin');
```

#### $git->remote->branches(_string_ $name, _array_ $branches)

Alias of set()

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->branches('origin', array('master', 'develop'));
```

#### $git->remote->branches->set(_string_ $name, _array_ $branches)

Changes the list of branches tracked by the named remote

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->branches->set('origin', array('master', 'develop'));
```

#### $git->remote->branches->add(_string_ $name, _array_ $branches)

Adds to the list of branches tracked by the named remote

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->branches->add('origin', array('master', 'develop'));
```

#### $git->remote->url(_string_ $name, _string_ $newUrl, _string_ $oldUrl = null, _array_ $options = [])

Alias of set()

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->url('origin', 'https://github.com/text/Text.git');
```

##### Options

- **push** (_boolean_) Push URLs are manipulated instead of fetch URLs

#### $git->remote->url->set(_string_ $name, _string_ $newUrl, _string_ $oldUrl = null, _array_ $options = [])

Sets the URL remote to $newUrl

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->url->set('origin', 'https://github.com/text/Text.git');
```

##### Options

- **push** (_boolean_) Push URLs are manipulated instead of fetch URLs

#### $git->remote->url->add(_string_ $name, _string_ $newUrl, _array_ $options = [])

Adds new URL to remote

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->url->add('origin', 'https://github.com/text/Text.git');
```

##### Options

- **push** (_boolean_) Push URLs are manipulated instead of fetch URLs

#### $git->remote->url->delete(_string_ $name, _string_ $url, _array_ $options = [])

Deletes all URLs matching regex $url

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
$git->remote->url->delete('origin', 'https://github.com');
```

##### Options

- **push** (_boolean_) Push URLs are manipulated instead of fetch URLs

* * * * *

### git reset

#### $git->reset(_string|array|\Traversable_ $paths, _string_ $commit = null)

Resets the index entries for all **$paths** to their state at **$commit**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset();
```

#### $git->reset->soft(_string_ $commit = null)

Resets the current branch head to **$commit**

Does not touch the index file nor the working tree at all (but resets the head to **$commit**,
just like all modes do).
This leaves all your changed files "Changes to be committed", as git status would put it.

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset->soft();
```

#### $git->reset->mixed(_string_ $commit = null)

Resets the current branch head to **$commit**

Resets the index but not the working tree (i.e., the changed files are preserved but not marked for commit)
and reports what has not been updated. This is the default action.

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset->mixed();
```

#### $git->reset->hard(_string_ $commit = null)

Resets the current branch head to **$commit**

Resets the index and working tree. Any changes to tracked files in the working tree since **$commit** are discarded

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset->hard();
```

#### $git->reset->merge(_string_ $commit = null)

Resets the current branch head to **$commit**

Resets the index and updates the files in the working tree that are different between **$commit** and HEAD,
but keeps those which are different between the index and working tree
(i.e. which have changes which have not been added). If a file that is different between **$commit** and
the index has unstaged changes, reset is aborted

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset->merge();
```

#### $git->reset->keep(_string_ $commit = null)

Resets the current branch head to **$commit**

Resets index entries and updates files in the working tree that are different between **$commit** and HEAD.
If a file that is different between **$commit** and HEAD has local changes, reset is aborted.

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset->keep();
```

#### $git->reset->mode(_string_ $mode, _string_ $commit = null)

Resets the current branch head to **$commit**

Possibly updates the index (resetting it to the tree of **$commit**) and the working tree depending on **$mode**

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->reset->mode('hard');
```

* * * * *

### git rm

#### $git->rm(_string|array|\Traversable_ $file, _array_ $options = [])

Remove files from the working tree and from the index

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->rm('CHANGELOG-1.0-1.1.txt', ['force' => true]);
```

##### Options

- **force**     (_boolean_) Override the up-to-date check
- **cached**    (_boolean_) Unstage and remove paths only from the index
- **recursive** (_boolean_) Allow recursive removal when a leading directory name is given

#### $git->rm->cached(_string|array|\Traversable_ $file, _array_ $options = [])

Equivalent to $git->rm($file, ['cached' => true]);

##### Options

- **force**     (_boolean_) Override the up-to-date check
- **recursive** (_boolean_) Allow recursive removal when a leading directory name is given

* * * * *

### git shortlog

#### $git->shortlog(_string|array|\Traversable_ $commits = HEAD)

Summarize 'git log' output

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$shortlog = $git->shortlog();
```

##### Output Example

``` php
[
    'John Doe <john@example.com>' => [
        0 => ['commit' => '589de67', 'date' => new \DateTime('2014-02-10 12:56:15 +0300'), 'subject' => 'Update README'],
        1 => ['commit' => '589de67', 'date' => new \DateTime('2014-02-15 12:56:15 +0300'), 'subject' => 'Update README'],
    ],
    //...
]
```

#### $git->shortlog->summary(_string_ $commits = HEAD)

Suppress commit description and provide a commit count summary only

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$shortlog = $git->shortlog->summary();
```

##### Output Example

``` php
[
    'John Doe <john@example.com>' => 153,
    //...
]
```

* * * * *

### git show

#### $git->show(_string_ $object, _array_ $options = [])

Shows one or more objects (blobs, trees, tags and commits)

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
echo $git->show('3ddee587e209661c8265d5bfd0df999836f6dfa2');
```

##### Options

- **format**        (_string_)  Pretty-print the contents of the commit logs in a given format, where <format> can be one of oneline, short, medium, full, fuller, email, raw and format:<string>
- **abbrev-commit** (_boolean_) Instead of showing the full 40-byte hexadecimal commit object name, show only a partial prefix

* * * * *

### git stash

#### $git->stash()

Save your local modifications to a new stash, and run git reset --hard to revert them

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash();
```

#### $git->stash->save(_string_ $message = null, _array_ $options = [])

Save your local modifications to a new stash, and run git reset --hard to revert them.

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash->save('My stash');
```

#### $git->stash->lists(_array_ $options = [])

Returns the stashes that you currently have

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$stashes = $git->stash->lists();
```

##### Output Example

``` php
[
    0 => ['branch' => 'master', 'message' => '0e2f473 Fixes README.md'],
    1 => ['branch' => 'master', 'message' => 'ce1ddde Initial commit'],
]
```

#### $git->stash->show(_string_ $stash = null)

Show the changes recorded in the stash as a diff between the stashed state and its original parent

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
echo $git->stash->show('stash@{0}');
```

##### Output Example

```
 REAMDE.md |    2 +-
 1 files changed, 1 insertions(+), 1 deletions(-)
```

#### $git->stash->drop(_string_ $stash = null)

Remove a single stashed state from the stash list

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash->drop('stash@{0}');
```

#### $git->stash->pop(_string_ $stash = null, _array_ $options = [])

Remove a single stashed state from the stash list and apply it on top of the current working tree state

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash->pop('stash@{0}');
```

#### $git->stash->apply(_string_ $stash = null, _array_ $options = [])

Like pop, but do not remove the state from the stash list

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash->apply('stash@{0}');
```

#### $git->stash->branch(_string_ $name, _string_ $stash = null)

Creates and checks out a new branch named <branchname> starting from the commit at which the <stash> was originally created, applies the changes recorded in <stash> to the new working tree and index

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash->branch('hotfix', 'stash@{0}');
```

#### $git->stash->clear()

Remove all the stashed states

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->stash->clear();
```

#### $git->stash->create()

Create a stash (which is a regular commit object) and return its object name, without storing it anywhere in the ref namespace

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$commit = $git->stash->create();
```

##### Output Example

```
877316ea6f95c43b7ccc2c2a362eeedfa78b597d
```

* * * * *

### git status

#### $git->status(_array_ $options = [])

Returns the working tree status

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$status = $git->status();
```

##### Constants

- StatusCommand::UNMODIFIED            [=' '] unmodified
- StatusCommand::MODIFIED              [='M'] modified
- StatusCommand::ADDED                 [='A'] added
- StatusCommand::DELETED               [='D'] deleted
- StatusCommand::RENAMED               [='R'] renamed
- StatusCommand::COPIED                [='C'] copied
- StatusCommand::UPDATED_BUT_UNMERGED  [='U'] updated but unmerged
- StatusCommand::UNTRACKED             [='?'] untracked
- StatusCommand::IGNORED               [='!'] ignored

##### Output Example

``` php
[
    'branch' => 'master',
    'changes' => [
        ['file' => 'item1.txt', 'index' => 'A', 'work_tree' => 'M'],
        ['file' => 'item2.txt', 'index' => 'A', 'work_tree' => ' '],
        ['file' => 'item3.txt', 'index' => '?', 'work_tree' => '?'],
    ]
]
```

##### Options

- **ignored** (_boolean_) Show ignored files as well

* * * * *

### git tag

#### $git->tag()

Returns an array of tags

``` php
$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
$git->setRepository('/path/to/repo');
$tags = $git->tag();
```

##### Output Example

```
['v1.0.0', 'v1.0.1', 'v1.0.2']
```

#### $git->tag->create(_string_ $tag, _string_ $commit = null, _array_ $options = [])

Creates a tag object

``` php
$git = new PHPGit\Git();
$git->setRepository('/path/to/repo');
$git->tag->create('v1.0.0');
```

##### Options

- **annotate** (_boolean_) Make an unsigned, annotated tag object
- **sign**     (_boolean_) Make a GPG-signed tag, using the default e-mail address’s key
- **force**    (_boolean_) Replace an existing tag with the given name (instead of failing)

#### $git->tag->delete(_string|array|\Traversable_ $tag)

Delete existing tags with the given names

#### $git->tag->verify(_string|array|\Traversable_ $tag)

Verify the gpg signature of the given tag names

* * * * *

### git ls-tree

#### $git->tree(_string_ $branch = master, _string_ $path = '')

Returns the contents of a tree object

``` php
$git = new PHPGit\Git();
$git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
$git->setRepository('/path/to/repo');
$tree = $git->tree('master');
```

##### Output Example

``` php
[
    ['mode' => '100644', 'type' => 'blob', 'hash' => '1f100ce9855b66111d34b9807e47a73a9e7359f3', 'file' => '.gitignore', 'sort' => '2:.gitignore'],
    ['mode' => '100644', 'type' => 'blob', 'hash' => 'e0bfe494537037451b09c32636c8c2c9795c05c0', 'file' => '.travis.yml', 'sort' => '2:.travis.yml'],
    ['mode' => '040000', 'type' => 'tree', 'hash' => '8d5438e79f77cd72de80c49a413f4edde1f3e291', 'file' => 'bin', 'sort' => '1:.bin'],
]
```

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@kzykhys)
