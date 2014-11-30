RestFabricationBundle
=====================

This bundle provides an approach for rapid development of RESTful APIs for your Symfony2 Project.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Codag/RestFabricationBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Codag/RestFabricationBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/codag/restfabrication-bundle/downloads.svg)](https://packagist.org/packages/codag/restfabrication-bundle)
[![Latest Stable Version](https://poser.pugx.org/codag/restfabrication-bundle/v/stable.svg)](https://packagist.org/packages/codag/restfabrication-bundle)
[![Latest Unstable Version](https://poser.pugx.org/codag/restfabrication-bundle/v/unstable.svg)](https://packagist.org/packages/codag/restfabrication-bundle)
[![License](https://poser.pugx.org/codag/restfabrication-bundle/license.svg)](https://packagist.org/packages/codag/restfabrication-bundle)

For further implementation examples please see also the following blog post: (coming soon).

## Installation

1. Add CodagRestFabricationBundle to your composer.json
2. Enable the bundle

### Step 1: Add CodagRestFabricationBundle to your composer.json
```js
{
    "require": {
        "codag/restfabrication-bundle": "dev-master"
    }
}
```

Update your project dependencies: 
```bash
php composer.phar update codag/restfabrication-bundle
```

### Step 2: Enable the bundle
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Codag\RestFabricationBundle\CodagRestFabricationBundle(),
    );
}
```

## Usage

### Domain Manager

The domain manager is a more abstract way to communicate with the data layer (e.g. Doctrine). As it takes an entity as an argument, the benefit will be high reusability.
Each ressource should be represented by it's own domain manager that can be easily defined within the [service container](http://symfony.com/doc/current/book/service_container.html) (services.xml). 

We therefore create a new services for all our ressources (entities) so that each time a new class of "codag_rest_fabrication.domain_manager.default.class" will be instatiated with the provided entity as an argument:

```php
<service id="acme_api.domain_manager.myresource" class="%codag_rest_fabrication.domain_manager.default.class%">
    <argument type="service" id="doctrine.orm.entity_manager" />
    <argument>AcmeApiBundle:Myresource</argument>
</service>
```
The newly created domain manager can now be used in a controller to prevent implementing duplicate code for action methods they represent simple restful (GET/DELETE) requests:

```php
    public function getAction()
    {
        return $this->get('acme_api.domain_manager.myresource')->findAll();
    }
```

```php
    public function deleteAction(Request $request, $id) {
        $manager = $this->get('acme_api.domain_manager.myresource');
        $obj = $manager->find($id);
        if(!$obj){
            throw new RessourceNotFoundException('Myresource', $id);
        }
        $manager->delete($itemId);

        return $this->routeRedirectView('myresource_all', array(), Codes::HTTP_NO_CONTENT);
    }
```

As we can see, the domain manager provides methods to find one or multiple entires and can directly remove entires. Please refer to the code for the full implementation set. 

### Form Handler

### Exceptions


##Contribute

If the bundle doesn't allow you to customize an option, I invite you to fork the project, create a feature branch, and send a pull request.

To ensure a consistent code base, you should make sure the code follows
the [Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html).


##License

This bundle is under the MIT license. See the complete license [here](https://github.com/Codag/PredictionIOBundle/blob/master/Resources/meta/LICENSE).


