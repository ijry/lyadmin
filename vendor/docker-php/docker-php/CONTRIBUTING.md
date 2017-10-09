# Contributing

If you're here, you would like to contribute to this repository and you're really welcome!


## Bug reports

If you find a bug or a documentation issue, please report it or even better: fix it :). If you report it,
please be as precise as possible. Here is a little list of required information:

 - Precise description of the bug
 - Details of your environment (for example: OS, PHP version, installed extensions)
 - Backtrace which might help identifing the bug


## Feature requests

If you think a feature is missing, please report it or even better: implement it :). If you report it, describe the more
precisely what you would like to see implemented and we will discuss what is the best approach for it. If you can do
some research before submitting it and link the resources to your description, you're awesome! It will allow us to more
easily understood/implement it.


## Sending a Pull Request

If you're here, you are going to fix a bug or implement a feature and you're the best! To do it, first fork the repository, clone it and create a new branch with the following commands:

``` bash
$ git clone git@github.com:your-name/docker-php.git
$ git checkout -b feature-or-bug-fix-description
```

Then install the dependencies through [Composer](https://getcomposer.org/):

``` bash
$ composer install
```

Write code and tests. When you are ready, run the tests. (This is usually [PHPUnit](http://phpunit.de/) or [PHPSpec](http://phpspec.net/))

``` bash
$ composer test
```

When you are ready with the code, tested it and documented it, you can commit and push it with the following commands:

``` bash
$ git commit -m "Feature or bug fix description"
$ git push origin feature-or-bug-fix-description
```

**Note:** Please write your commit messages in the imperative and follow the [guidelines](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html) for clear and concise messages.

Then [create a pull request](https://help.github.com/articles/creating-a-pull-request/) on GitHub.

Please make sure that each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please squash them before submitting with the following commands (here, we assume you would like to squash 3 commits in a single one):

``` bash
$ git rebase -i HEAD~3
```

If your branch conflicts with the master branch, you will need to rebase and repush it with the following commands:

``` bash
$ git remote add upstream git@github.com:docker-php/docker-php.git
$ git pull --rebase upstream master
$ git push -f origin feature-or-bug-fix-description
```

## Internal

This library consist, for the most part, of auto generated code where the reference is an [Open API Specification (Swagger v2)](https://openapis.org/). In order to modify API 
endpoint or requested / returned object, you will need to update the `docker-swagger.json` file instead of files in the `generated` directory.

There is a bash script at the root of this repository `generate.sh` which helps launching the command to generate files according to the
specification.

When changing the specification don't hesite to do 2 commits for better reading:

 * One with only changes to the specification
 * One with changes on the generated code

Having this also helps backporting changes to the specification in previous versions.


## Coding standard

This repository follows the [PSR-2 standard](http://www.php-fig.org/psr/psr-2/) and so, if you want to contribute,
you must follow these rules.


## Semver

We are trying to follow [semver](http://semver.org/). When you are making BC breaking changes, please let us know why you think it is important. In this case, your patch can only be included in the next major version.
