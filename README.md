# Form Bundle

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/form-bundle.svg)](https://packagist.org/packages/endroid/form-bundle)
[![Build Status](https://github.com/endroid/form-bundle/workflows/CI/badge.svg)](https://github.com/endroid/form-bundle/actions)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/form-bundle.svg)](https://packagist.org/packages/endroid/form-bundle)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/form-bundle.svg)](https://packagist.org/packages/endroid/form-bundle)
[![License](http://img.shields.io/packagist/l/endroid/form-bundle.svg)](https://packagist.org/packages/endroid/form-bundle)

The Endroid FormBundle enables users to create basic forms through the Sonata
Admin Bundle. It includes form configuration, storage of results, sending of
notifications when a form is filled in and even the possibility to export form
results.

[![knpbundles.com](http://knpbundles.com/endroid/form-bundle/badge-short)](http://knpbundles.com/endroid/form-bundle)

## Requirements

* Symfony

## Installation

Use [Composer](https://getcomposer.org/) to install the bundle.

``` bash
$ composer require endroid/form-bundle
```

Then enable the bundle via the kernel.

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Endroid\FormBundle\EndroidFormBundle(),
    ];
}
```

## Usage

Forms are created via Sonata Admin and can be rendered directly in your
template as follows.

```php
{{ render(controller('EndroidFormBundle:Form:show', { form: form })) }}
```

When a form is filled in correctly a result is generated and a form success
event is dispatched. You can specify via Sonata Admin whether this result
should be stored and if a confirmation email should be sent.

## Extension

### Event listeners

Event listeners exist for storing the result and sending a confirmation mail
(depending on your settings). You can extend this functionality by adding your
own listener or by overriding any of the existing.

```php
/**
 * {@inheritdoc}
 */
public static function getSubscribedEvents()
{
    return [FormSuccessEvent::NAME => 'myHandler'];
}

/**
 * My custom form success handler.
 *
 * @param FormSuccessEvent $event
 */
public function myHandler(FormSuccessEvent $event)
{
    $result = $event->getResult();
    $form = $result->getForm();

    //...
}
```

And register it via the service container.

```php
acme.event_listener.my_listener:
    class: Acme\EventListener\MyListener
    tags:
        - { name: kernel.event_subscriber }
```

### Fields

New fields can be added by extending the Field class, creating a service
definition for the the new field and adding the tag "endroid_form.field".

```php
acme.field.my_field:
    class: Acme\Entity\MyField
    tags:
        - { name: endroid_form.field }
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
