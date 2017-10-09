phantomjs-installer
===================

[![Latest Stable Version](https://poser.pugx.org/jakoch/phantomjs-installer/version.png)](https://packagist.org/packages/jakoch/phantomjs-installer)
[![Total Downloads](https://poser.pugx.org/jakoch/phantomjs-installer/d/total.png)](https://packagist.org/packages/jakoch/phantomjs-installer)
[![Build Status](https://travis-ci.org/jakoch/phantomjs-installer.png)](https://travis-ci.org/jakoch/phantomjs-installer)
[![License](https://poser.pugx.org/jakoch/phantomjs-installer/license.png)](https://packagist.org/packages/jakoch/phantomjs-installer)

A Composer package which installs the PhantomJS binary (Linux, Windows, Mac) into `/bin` of your project.

##### Table of Contents 

- [Installation](#installation)
- [How to require a specific version of PhantomJS?](#how-to-require-specific-versions-of-phantomjs)
- [How does this work internally?](#how-does-this-work-internally)
- [How to access the binary easily by using PhantomInstaller\PhantomBinary?](#phantombinary)
- [How to package for another platform by overriding platform requirements?](#override-platform-requirements)
- [How to use a Mirror or a custom CDN URL for downloading?](#downloading-from-a-mirror)

## Installation

To install PhantomJS as a local, per-project dependency to your project, simply add a dependency on `jakoch/phantomjs-installer` to your project's `composer.json` file.


```json
{
    "require": {
        "jakoch/phantomjs-installer": "2.1.1-p08"
    },
    "config": {
        "bin-dir": "bin"
    },
    "scripts": {
        "post-install-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ],
        "post-update-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ]
    }
}
```

For a development dependency, change `require` to `require-dev`.

The default download source used is: https://bitbucket.org/ariya/phantomjs/downloads/
You might change it by setting a custom CDN URL, which is explained in the section "[Downloading from a mirror](#downloading-from-a-mirror)".

By setting the Composer configuration directive `bin-dir`, the [vendor binaries](https://getcomposer.org/doc/articles/vendor-binaries.md#can-vendor-binaries-be-installed-somewhere-other-than-vendor-bin-) will be installed into the defined folder.
**Important! Composer will install the binaries into `vendor\bin` by default.**

The `scripts` section is necessary, because currently Composer does not pass events to the handler scripts of dependencies. If you leave it away, you might execute the installer manually.

Now, assuming that the scripts section is set up as required, the PhantomJS binary
will be installed into the `/bin` folder and updated alongside the project's Composer dependencies.

## How to require specific versions of PhantomJS?

1. The version number of the package specifies the PhantomJS version. 
   When you specify:
    - `2.1.1-p07`: Composer fetches the 2.1.1-p07 tag of the installer. The installer fetches the 2.1.1 version of PhantomJS.
    - `2.1.1`: Composer fetches the 2.1.1 tag of the installer. The installer fetches the 2.1.1 version of PhantomJS.
    - `1.9.8`: Composer fetches the 1.9.8 tag of the installer. The installer fetches the 1.9.8 version of PhantomJS. 
      - This will also fetch an old installer tag. Please use the syntax for a version alias instead (see item 3 below).
    - **Important! Please use exact versioning, e.g. `2.1.1-p07`. Do not use a wildcard or caret operator, e.g. `^2.1`, as this will not resolve to the latest patch level version.**
2. If you specify `dev-master`, the latest version will be fetched.
  - Composer fetches the latest version of the installer. The installer fetches the latest version of PhantomJS.
3. You might also specify the PhantomJS version by using a version alias,  e.g. `dev-master as <version>`. 
  - Composer fetches the latest version of the installer. The installer fetches `<version>` of PhantomJS!
4. If you specify an explicit commit reference  with a version alias, e.g. `dev-master#<commit-ref> as <version>`.
  - Composer fetches a specific git commit of the installer. The installer fetches `<version>` of PhantomJS!
  
You find more details on the versioning scheme used by the installer in the [comments of issue 39](https://github.com/jakoch/phantomjs-installer/issues/39).

## How does this work internally?

1. **Fetching the PhantomJS Installer**

 In your composer.json you require the package "phantomjs-installer".
 The package is fetched by composer and stored into `./vendor/jakoch/phantomjs-installer`.
 It contains only one file the `PhantomInstaller\\Installer`.

2. **Platform-specific download of PhantomJS**

 The `PhantomInstaller\\Installer` is run as a "post-install-cmd". That's why you need the "scripts" section in your "composer.json".
 The installer creates a new composer in-memory package "phantomjs",
 detects your OS and downloads the correct Phantom version to the folder `./vendor/jakoch/phantomjs`.
 All PhantomJS files reside there, especially the `examples`.

3. **Installation into `/bin` folder**

 The binary is then copied from `./vendor/jakoch/phantomjs` to your composer configured `bin-dir` folder.

4. **Generation of PhantomBinary**

 The installer generates a PHP file `PhantomInstaller\\PhantomBinary` and inserts the path to the binary.

## PhantomBinary

To access the binary and its folder easily, the class `PhantomBinary` is created automatically during installation.

The class defines the constants `BIN` and `DIR`:
  - `BIN` is the full-path to the PhantomJS binary file, e.g. `/your_project/bin/phantomjs`
  - `DIR` is the folder of the binary, e.g. `/your_project/bin`

Both constants are also accessible via their getter-methods `getBin()` and `getDir()`.

Usage:

    use PhantomInstaller\PhantomBinary;

    // get values with class constants

    $bin = PhantomInstaller\PhantomBinary::BIN;
    $dir = PhantomInstaller\PhantomBinary::DIR;

    // get values with static functions

    $bin = PhantomInstaller\PhantomBinary::getBin();
    $dir = PhantomInstaller\PhantomBinary::getDir();

This feature is similar to `location.js` of the [phantomjs module](https://github.com/Medium/phantomjs/blob/master/install.js#L93) for Node.

## Override platform requirements

The environment and server variables `PHANTOMJS_PLATFORM` and `PHANTOMJS_BITSIZE` enable you to
override the platform requirements at the time of packaging. This decouples the packaging system
from the target system. It allows to package on Linux for MacOSX or on Windows for Linux.

Possible values for
 - `PHANTOMJS_PLATFORM` are: `macosx`, `windows`, `linux`.
 - `PHANTOMJS_BITSIZE` are: `32`or `64`.

## Downloading from a mirror

You can override the default download location of the PhantomJS binary file by setting it in one of these locations. Listed in order of precedence (highest first):
* The environment variable `PHANTOMJS_CDNURL`
* The server variable `PHANTOMJS_CDNURL`
* In your `composer.json` by using `$['extra']['jakoch/phantomjs-installer']['cdnurl']`:

 ```json
  "extra": {
    "jakoch/phantomjs-installer": {
      "cdnurl": "https://github.com/Medium/phantomjs/releases/download/v1.9.19/"
    }
  },
 ```

**Default Download Location**

The default download location is Bitbucket: `https://api.bitbucket.org/2.0/repositories/ariya/phantomjs/downloads/`.
You don't need to set it explicitly. It's used, when `PHANTOMJS_CDNURL` is not set.

**Mirrors**

You might use one of the following mirror URLs as a value for `PHANTOMJS_CDNURL`:
  - `https://cnpmjs.org/downloads/` - USA, San Mateo (47.88.189.193)
  - `https://npm.taobao.org/mirrors/phantomjs/` - China, Hangzhou (114.55.80.225)
  - `https://github.com/Medium/phantomjs/` - USA, San Francisco (192.30.253.113)

This list of mirrors is not complete. If you know another mirror, please don't hesitate to add it here.

The mirror URLs are also not hardcoded, except for the Github URL. 
This enables you to point to any PhantomJS mirror or download folder you like.
For instance, you could point to the URL of the download folder of your company, where the binaries are stored: 
`PHANTOMJS_CDNURL=https://cdn.company.com/downloads/phantomjs/`.

## Automatic download retrying with version lowering on 404

In case downloading an archive fails with HttpStatusCode 404 (resource not found),
the downloader will automatically lower the version to the next available version
and retry. The number of retries is determined by the number of hardcoded PhantomJS
versions in `getPhantomJSVersions()`. This feature was added, because of the problems
with v2.0.0 not being available for all platforms (see issue #25).
