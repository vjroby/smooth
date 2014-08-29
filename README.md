Smooth MVC Framework
====================

###This is a basic Model-View-Controller framework developed by me from my experience with Codeigniter, CakePHP and Yii.
You can find more about me [Here](http://robertgabriel.ro)

##INSTALATION

The web server must support mod_rewrite with .htaccess files. The .htaccess fie from webroot redirects all requests to ./public directory. The second .htaccess file, from ./public, creates two query parameters of type GET _url_ and _extension_ .
If the _url_ isn't set as a $_GET['url'] in PHP then the framework loads the default controller and action.

The configuration files are located in ./application/configuration/ .There are ini files and are pretty straight forward.

There is also a routes.php file located in ./public/ from there you can configure your own routes, there are set as objects.

##Changelog

*vre 0.5
    * changed where functionality in mysql queries
    * added join in query class
    * view for api
    * route for api with HTTP methods
    * listen for HTTP methods if necessary

* ver 0.4.1
    * integrated language files with xml
    * changed .htaccess to accept parameters with colon (name:value)

* ver 0.4
    * integrated Web Starter Kit from Google
    * integrated SASS
    * integrated socket.io server side

* ver. 0.3.1
    * thumbnails functionality with Imagine
    * integrated Imagine library in project
    * Events class
    * put events in core classes
    * Logger plugin

* ver. 0.3.1
    * implemented AJAX navigation based on AJAX request

* ver. 0.3
    * starting to build a social network as an example
    * register, login, logout, user profile and settings
    * no password hashing is used yet, will use blowfish

* ver. 0.2
    * Html Class for creating form elements, input and select for now
    * Request Class for getting the host and base URL

* ver 0.1.2
    * Test functionality for unit testing

* ver. 0.1.1
    * implemented exceptions
    * implemented utilities


* ver. 0.1
    * implemented namespaces
    * implemented autoloader