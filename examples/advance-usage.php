<?php
require_once '../vendor/autoload.php';

use IcecastAuth\IcecastAuth;

$IceAuth = new IcecastAuth();

// Setup
$IceAuth->setAuthCallback('\authFunc');  // set the callback for authentification
$IceAuth->setAuthErrorCallback('\logError');
$IceAuth->setAddMountCallback('\logAddMount');
$IceAuth->setRemoveMountCallback('\logRemoveMount');
$IceAuth->setAddListenerCallback('\logAddList');
$IceAuth->setRemoveListenerCallback('\logRemoveList');
$IceAuth->setAuthHeader('HTTP/1.1 200 OK');
        
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

// Will log Authetication errors in the a file
function logErrors($parameters){
    _log('Acces refused',$parameters);
}
function logAddList($parameters){
    _log('Listener added',$parameters);
}
function logRemoveList($parameters){
    _log('Listener removed',$parameters);
}
function logAddMount($parameters){
    _log('Mount removed',$parameters);
}
function logRemoveMount($parameters){
    _log('Acces refused');
}

function _log($message,$parameters){
    error_log($message.' ('.implode('-', $parameters).')',3,'./'.date("Ymd").'icecast.log');
}

/* MOUNT CONFIGURATION ON ICECAST SERVER
<mount>
    <mount-name>/mystream.mp3</mount-name>
    <authentication type="url">
    	<option name="mount_add" value="[URL OF THIS SCRIPT]"/>
        <option name="mount_remove" value="[URL OF THIS SCRIPT]"/>
        <option name="listener_add" value="[URL OF THIS SCRIPT]"/>
        <option name="listener_remove" value="[URL OF THIS SCRIPT]"/>
        <option name="auth_header" value="HTTP/1.1 200 OK"/>
    </authentication>
</mount>  
 */
?>
