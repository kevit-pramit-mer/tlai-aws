Authentication Management
=========================
Login, Forgot Password, Reset Password

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ecosmob/auth "*"
```

or add

```
"ecosmob/auth": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \ecosmob\auth\AutoloadExample::widget(); ?>```