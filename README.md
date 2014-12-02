RestFabricationBundle
=====================

This bundle provides an approach for rapid development of RESTful APIs for your Symfony2 Project.

[![Build Status](https://travis-ci.org/Codag/RestFabricationBundle.svg?branch=master)](https://travis-ci.org/Codag/RestFabricationBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Codag/RestFabricationBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Codag/RestFabricationBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/codag/restfabrication-bundle/downloads.svg)](https://packagist.org/packages/codag/restfabrication-bundle)
[![Latest Stable Version](https://poser.pugx.org/codag/restfabrication-bundle/v/stable.svg)](https://packagist.org/packages/codag/restfabrication-bundle)
[![Latest Unstable Version](https://poser.pugx.org/codag/restfabrication-bundle/v/unstable.svg)](https://packagist.org/packages/codag/restfabrication-bundle)
[![License](https://poser.pugx.org/codag/restfabrication-bundle/license.svg)](https://packagist.org/packages/codag/restfabrication-bundle)

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

For further implementation examples please see also the following blog post: (coming soon).

### Domain Manager

The domain manager is a more abstract way to communicate with the data layer (e.g. Doctrine). As it takes an entity as an argument, the benefit will be high reusability.
Each resource should be represented by it's own domain manager that can be easily defined within the [service container](http://symfony.com/doc/current/book/service_container.html) (services.xml). 

We therefore create new services for all our resources (entities) so that each time a new class of "codag_rest_fabrication.domain_manager.default.class" will be instatiated with the provided entity as an argument:

```php
<service id="acme_api.domain_manager.myresource" class="%codag_rest_fabrication.domain_manager.default.class%">
    <argument type="service" id="doctrine.orm.entity_manager" />
    <argument>AcmeApiBundle:Myresource</argument>
</service>
```
The created domain manager can now be used in a controller to prevent implementing duplicate code for action methods they represent simple restful (GET/DELETE) requests:

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
        throw new ResourceNotFoundException('Myresource', $id);
    }
    $manager->delete($id);
    return $this->routeRedirectView('myresource_all', array(), Codes::HTTP_NO_CONTENT);
}
```

As we can see, the domain manager provides methods to find one or multiple entires and can directly remove entries. Please refer to the code for the full implementation set. 

### Form Handler

The form handler relies on the domain manager and provides processing of forms created during the process of an incoming PUT/POST request. 

We therefore create new services for all our resources (entities) they relie on forms so that each time a new class of "codag_rest_fabrication.form_handler.create_form.class" will be instatiated. As a single argument the resource related domain manager has to be provided:

```php
<service id="acme_api.form_handler.myresource" class="%codag_rest_fabrication.form_handler.create_form.class%">
    <argument type="service" id="acme_api.domain_manager.myresource" />
</service>
```

The created form manager can now be used in a controller to prevent implementing duplicate code for action methods they represent simple restful (PUT/POST) requests and have to be processed with forms:

```php
public function postAction(Request $request){
    try {
        $form = $this->createForm(new MyresourceType(), new Myresource(), array('method' => 'POST'));
        $new = $this->get('acme_api.form_handler.myresource')->handle($form, $request);
        
        return $this->routeRedirectView('myresource_get', array('id' => $new->getId()), Codes::HTTP_CREATED);
    }catch (InvalidFormException $exception) {
        return $exception->getForm();
    }
}
```

```php
public function putAction(Request $request, $id){
    try {
        $manager = $this->get('acme_api.domain_manager.myresource');
        $formHandler = $this->get('acme_api.form_handler.myresource');

        if (!($object = $manager->get($id))) {
            $statusCode = Codes::HTTP_CREATED;
            $form = $this->createForm(new MyresourceType(), new Myresource(), array('method' => 'POST'));
        } else {
            $statusCode = Codes::HTTP_NO_CONTENT;
            $form = $this->createForm(new MyresourceType(), $object, array('method' => 'PUT'));
        }

        $object = $formHandler->handle($form, $request);
        
        return $this->routeRedirectView('myresource_get_all', array('id' => $object->getId()), $statusCode);
    } catch (InvalidFormException $exception) {
        return $exception->getForm();
    }
}
```

### Exceptions

#### InvalidFormException

To be able to deal with a clean error management, this exception can be used whenever a form is processed. The exception can be thrown during the process of the form handler. For validation purposes then exception will then be catched within the controller to finally return the form to the view. 

Form Handler:
```php
if($form->isValid()){
    ...
}
throw new InvalidFormException('Invalid submitted data', $form);
```

Controller:
```php
try {
    ...
} catch (InvalidFormException $exception) {
    return $exception->getForm();
}
```

#### ResourceNotFoundException

To not constantly repeat yourself, this exception is a wrapper of the NotFoundHttpException that holds a standard sentence. In addition it takes the resource name as well as the value of the identifier.

Controller:
```php
throw new ResourceNotFoundException('Myresource', $id);
```

Output:
```json
{
  "code": 404,
  "message": "Myresource not found with id: 123"
}
```

#### RestException

For the sake of completeness this exception is a wrapper of the HttpException and may be extended in the near future. 

##Contribute

If the bundle doesn't allow you to customize an option, I invite you to fork the project, create a feature branch, and send a pull request.

To ensure a consistent code base, you should make sure the code follows
the [Coding Standards](http://symfony.com/doc/current/contributing/code/standards.html).


##License

This bundle is under the MIT license. See the complete license [here](https://github.com/Codag/PredictionIOBundle/blob/master/Resources/meta/LICENSE).


