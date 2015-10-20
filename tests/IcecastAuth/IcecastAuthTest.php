<?php

use IcecastAuth\IcecastAuth;

class IcecastAuthTest extends PHPUnit_Framework_TestCase {
 
public function testTestSetCallbackFunc()
{
    $setMethods = array('setAuthCallback','setAuthErrorCallback','setAddListenerCallback','setRemoveListenerCallback','setAddMountCallback','setRemoveMountCallback');
    
    foreach ($setMethods as $method) {
        $iceAuth = new IcecastAuth();
        $this->assertTrue($iceAuth->$method('testFunc'),$method.'-func-OK : '.$iceAuth->getLastError());
        $this->assertTrue($iceAuth->$method(array( new testClass(),'testFunc')),$method.'-method-OK : '.$iceAuth->getLastError());
        $this->assertFalse($iceAuth->$method('toto'),$method.'-func-NOK : '.$iceAuth->getLastError());
        $this->assertFalse($iceAuth->$method(array( new testClass(),'toto')),$method.'-method-NOK : '.$iceAuth->getLastError());
    }
    
}
  
  
 
}
class testClass{
    public function testFunc($paramaters){
        return true;
    } 
}

function testFunc($paramaters){
      return true;
}

?>
