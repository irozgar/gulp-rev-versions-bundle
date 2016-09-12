GulpRevVersionsBundle  
=====================
[![Build Status](https://travis-ci.org/irozgar/gulp-rev-versions-bundle.svg?branch=master)](https://travis-ci.org/irozgar/gulp-rev-versions-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f85b2945-53fe-4511-b3f0-ab93111b62a2/mini.png)](https://insight.sensiolabs.com/projects/f85b2945-53fe-4511-b3f0-ab93111b62a2)

This bundle helps you using your assets versioned with gulp-rev in a symfony project by
making the twig function `asset` return the files mapped in your gulp-rev manifest.

Installation
------------

### Step 1. Download with composer

```bash
composer require irozgar/gulp-dev-versions-bundle
```

### Step 2. Add the bundle to AppKernel

```php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Irozgar\GulpRevVersionsBundle\IrozgarGulpRevVersionsBundle(),
        );
    }
}
```

### Step 3. Configure your bundle

The configuration of the bundle depends on the symfony version

#### Symfony version < 3.1

Now you have to make the bundle replace the default version strategy for your assets.
To make this add the following to your config.yml file:
```yml
# app/config/config.yml

irozgar_gulp_rev_versions:
    replace_default_version_strategy: ~
```

#### Symfony version = 3.1 

This symfony version introduced a new option to configure the version strategy.

Add this to your config.yml to tell symfony what version strategy it should use
```yaml
# app/config/config.yml

framework:
    # ...
    assets:
        version_strategy: irozgar_gulp_dev_versions.asset.gulp_rev_version_strategy
       
# ...

# This is only needed if using a custom path for the manifest file
irozgar_gulp_rev_versions:
    manifest_path: "your/custom/path/rev-manifest.yml"
```

#### Configuring the manifest file path

The default location of the rev-manifest.json file is _app/Resources/assets/rev-manifest.json_.
You can customize it by adding the following lines to your config.yml

```yaml
# app/config/config.yml

irozgar_gulp_rev_versions:
    manifest_path: "your/custom/path/rev-manifest.yml"
```

**NOTE** All paths will be relative to `%kernel.root_dir%`
