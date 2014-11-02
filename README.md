Lookup Lists Plugin
========================

The **Lookup Lists** plugin enables developers to create global lists that are accessible in all your models, controllers and views 

You can create lists for statuses, groupings or any database field that has more than one option. For example: if Users can have multiple statuses, you can create a list for all the statuses.

Requirements
------------

* CakePHP 2.5.4+
* PHP 5.2.8+


Instalation
-----------

* Clone the repo into /app/Plugins/LookupLists
* Run the Config/Schema/LookupLists.sql script to create the needed tables
* Enable the plugin by adding CakePlugin::load('LookupLists'); to your bootstrap.php


Usage
-----

You can add the model behavoir in the models that has lists

    public $actsAs = array('LookupLists.Lists' => array(
            'fields' => array(
                'status' => 'user_statuses',
                'type' => 'user_types'
            ),
    ));

Where [status] is the field in your users table and [user_statuses] is the name of the list. When a find is done for the model, the plugin will add fields to your result set. 2 fields will be added [status]_value and [status]_slug.
