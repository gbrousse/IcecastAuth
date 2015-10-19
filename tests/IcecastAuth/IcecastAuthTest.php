<?php

namespace IcecastAuth;

class IcecastAuthTest extends \PHPUnit_Framework_TestCase {
 
  public function testAddAuthFunc()
  {
    $iceAuth = new IcecastAuth();
    $this->assertTrue($iceAuth->setAuthCallback('testFunc'),$iceAuth->getLastError());
    $this->assertTrue($iceAuth->setAuthCallback(array( new testClass(),'testFunc')),$iceAuth->getLastError());
    $this->assertFalse($iceAuth->setAuthCallback('toto'),$iceAuth->getLastError());
    $this->assertFalse($iceAuth->setAuthCallback(array( new testClass(),'toto')),$iceAuth->getLastError());
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
