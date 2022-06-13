# Backend Coding Challenge

## Directory Overview

The main file of interest is located at [`src/Controller/OrderController.php`](src/Controller/OrderController.php).
All Entities are defined in `src/Entity` and are managed by Doctrine.
Any other files are default Symfony 6 files which you don't need to pay attention to.

## Challenge / Task

Please have a look at the [OrderController](src/Controller/OrderController.php) and perform a code review on it. Think about where any errors could lie, what issues could arise in the long-term and what parts you would improve/refactor. 

Feel free to take notes if you want to but don't jump ahead and fix the code. After your reading time is over, please share your screen and guide us through the issues that you've found.

Any questions? :-)

## Hints

If you aren't familiar with Doctrine yet, here is an extract from the documentation about persisting and flushing entities:

> An entity can be made persistent by passing it to the `EntityManager#persist($entity)` method. By applying the persist operation on some entity, that entity becomes MANAGED, which means that its persistence is from now on managed by an EntityManager. As a result the persistent state of such an entity will subsequently be properly synchronized with the database when `EntityManager#flush()` is invoked.    

> Invoking the `persist` method on an entity does NOT cause an immediate SQL INSERT to be issued on the database. Doctrine applies a strategy called transactional write-behind, which means that it will delay most SQL commands until `EntityManager#flush()` is invoked which will then issue all necessary SQL statements to synchronize your objects with the database in the most efficient way and a single, short transaction, taking care of maintaining referential integrity.
