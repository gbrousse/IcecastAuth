# IcecastAuth [![Build Status](https://travis-ci.org/gbrousse/IcecastAuth.svg?branch=master)](https://travis-ci.org/gbrousse/IcecastAuth)[![Coverage Status](https://coveralls.io/repos/gbrousse/IcecastAuth/badge.svg?branch=master&service=github)](https://coveralls.io/github/gbrousse/IcecastAuth?branch=master)

[![Total Downloads](https://img.shields.io/packagist/dt/gbrousse/icecast-auth.svg)](https://packagist.org/packages/gbrousse/icecast-auth)
[![Latest Stable Version](https://img.shields.io/packagist/v/gbrousse/icecast-auth.svg)](https://packagist.org/packages/gbrousse/icecast-auth)

PHP library used to authenticate listeners via Icecast authentication (URL) (http://icecast.org/docs/icecast-2.4.1/auth.html).
This library execute your own functions or methods on Icesast server events.

## Installation

Install the latest version with

```bash
$ composer require gbrousse/icecast-auth
```

## Basic usage

### Create a PHP File that the Icecast server can reach
```php
<?php

use IcecastAuth\IcecastAuth; 
$IceAuth = new IcecastAuth();

// Setup
$IceAuth->setAuthCallback('function1'); // REQUIRED : Set the function call for the authentication 
        
// Execute
$IceAuth->execute();

```

Setup callback function will receive as argument an array containing : 
- server : domain of the Icecast server
- port : the port use to call the stream
+ client : an unique id set by Icecast for the listener
+ mountpoint : the mount called by listener
+ ip : ip of the listener
+ all GET parameters in the stream url called by the listener

### Configure mount on Icecast server
```
<mount>
    <mount-name>/example.ogg</mount-name>
    <authentication type="url">
    	<option name="mount_add" value="[URL OF THE SCRIPT YOU CREATE ABOVE]"/>
        <option name="auth_header" value="icecast-auth-user: 1"/>
        <option name="timelimit_header" value="icecast-auth-timelimit:"/>
    </authentication>
</mount>
```


## Examples

- [Basic Usage](examples/basic-usage.php)
- [Advance Usage](examples/advance-usage.php)


## About

### Requirements

- IcecastAuth works with PHP 5.3 or above.

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/gbrousse/IcecastAuth/issues)

### Author

Gregory Brousse - <pro@gregory-brousse.fr> - <http://gregory-brousse.fr>

### License

IcecastAuth is licensed under the LGPL-3.0 License - see the `LICENSE` file for details

