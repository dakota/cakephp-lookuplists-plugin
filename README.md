CakePHP Lookup Lists Plugin
===========================

Version: 0.2
------------

The **Lookup Lists** plugin enables developers to create global lists that are accessible in all your models, controllers and views. This gives you a central place to manage all lists that are used in your app.

You can create lists for statuses, groupings or any database field that has more than one option. For example: if Users can have multiple statuses, you can create a list for all the statuses.

Because the lists are decoupled from the model, it allows you to change the value of any of the list items without having an impact on the rest of your database, making sure that database integrity is preserved.

The master branch is always the stable branch. Work continues on version branches.

Requirements
------------

* CakePHP 2.5.4+
* PHP 5.2.8+
* Twitter Bootstrap 3.0+ (Only needed for management interfaces)
* jQuery 1.9+ (Only needed for management interfaces)


Instalation
-----------

* Clone the repo into /app/Plugins/LookupLists.
* Run the Config/Schema/LookupLists.sql script to create the needed tables.
* Enable the plugin by adding CakePlugin::load('LookupLists'); to your bootstrap.php

Structure
-----------

**LookupList** hasMany **LookupListItem**

> The LookupList model must have a unique slug

Usage
-----

**In Models**

You can add the model behavoir in the models that has lists:

```php
public $actsAs = array('LookupLists.Lists' => array(
        'fields' => array(
            'status' => 'user_statuses',
            'type' => 'user_types'
        ),
));
```

Where [status] is the field in your users table and [user_statuses] is the name of the list. When a find is done for the model, the plugin will add fields to your result set. 2 fields will be added [status]_value and [status]_slug.

**In Controllers**

To read a value from a lookup list, you can use the following code:

```php
$this->User->getLookupListItemId('status', 'deleted');
```

This will return the item_id for the `status` where the slug is equal to `deleted`

**In Views**

You can use the plugin to populate `<select>` form controllers.

You will need to init the LookupList helper in your controller

```php
public $helpers = array('LookupLists.LookupList');
````

In your form, you can use the following code to generate the select form control

```php
<?php echo $this->LookupList->makeList('status', 'user_statuses'); ?>
```

status is the field that you would like to update, and user_statuses is the list slug that you want to use. This will only retrieve list items that are set as public


----------


Changelog
-----

**0.2: Initial Release - ?? November 2014**
* New Interface (Using Standard Twitter Bootstrap & jQuery)
* Improved validation rules


**0.1: Initial Release - 2 November 2014**

To Do
-----

* Need a better interface to manage lists and list items
* Improved routing to views & controllers
* Need to improve data validation
* Complete the import & export functionality
* Test cases


If you find any issues with the plugin, please create a new issue within [GitHub](https://github.com/jacoroux/cakephp-lookuplists-plugin/issues)