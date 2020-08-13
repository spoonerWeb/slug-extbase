# TYPO3 extension "slug_extbase"

## What does it do?

In general, the slug field is updated on every change in backend or via DataHandler.<br>
But using forms in frontend to change Extbase models doesn't have an effect on the slug field.

This extensions provides an interface to use in your Extbase model.<br>
With a proper TCA configuration it automatically updates the slug field if one of the configured
table fields was changed.

## Installation

### Via composer

1. `composer require spooner-web/slug-extbase`

### Via Extension Manager

1. Download the extension from TER or inside EM
1. Activate the package

## Usage

Add the provided interface `\SpoonerWeb\SlugExtbase\SlugEntityInterface` to your Extbase model, e.g.

```php

class MyModel extends TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements \SpoonerWeb\SlugExtbase\SlugEntityInterface

```

That's it.

Okay, you need a slug field in database and the [TCA configuration](https://docs.typo3.org/m/typo3/reference-tca/master/en-us/ColumnsConfig/Type/Slug.html) for it as well.

Whenever you create or update an Extbase model via a frontend form, the magic happens and the slug field 
will be updated like you configured it. 
