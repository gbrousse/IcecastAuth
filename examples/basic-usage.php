<?php
require_once '../vendor/autoload.php';

use IcecastAuth\IcecastAuth;

$IceAuth = new IcecastAuth();

// Setup
$IceAuth->setAuthCallback('\authFunc'); // set the callback for authentification
        
// Execute
$IceAuth->execute();

// Authetication will work if there is a parameter azerty in the stream url called by user (ex : http://myserver.com/mystream.mp3?azerty=1)
function authFunc($parameters){
    if (isset($parameters['azerty'])){
        return true;
    }else{
        return false;
    }
}

/* MOUNT CONFIGURATION ON ICECAST SERVER
<mount>
    <mount-name>/mystream.mp3</mount-name>
    <authentication type="url">
    	<option name="listener_add" value="[URL OF THIS SCRIPT]"/>
        <option name="auth_header" value="icecast-auth-user: 1"/>
    </authentication>
</mount>  
 */

?>
