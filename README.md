# Interplanetravels
Example of an e-shop website using Symfony, React, vanilla JS, Ajax, Twig, Sass and Bootstrap.

Comments in the code are in French.

## The idea for the project

Interplanetravels is an imaginary travelling agency allowing to book travels to other planets.

The website is similar to one of a travel company, with travels to different destinations under a schedule that the customer can book or bookmark, and that the manager can create, plan and promote.

## Symfony and Twig

The basis of the website is a database managed through Symfony (with the help of Doctrine) and displayed using Twig templates.

## React and JS

The dynamic Searchbar is using React. 

The rest is vanilla JS, with the occasional use of Ajax on the forms that require to fill a list of options with entries from the DB. Ex: saved payment methods.

## CSS

To speed up the process, Bootstrap is used as a basis and is still visible on forms. The layer of customisation above is made with Sass.

## CMS

To stay simple, there isn't any payment CMS. The payment process is simulated through pages that will never really reach a bank.
