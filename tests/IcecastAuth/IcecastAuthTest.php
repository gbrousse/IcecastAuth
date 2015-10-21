<?php

use IcecastAuth\IcecastAuth;

class IcecastAuthTest extends PHPUnit_Framework_TestCase {
 
    /**
     * @covers IcecastAuth\IcecastAuth::setAuthCallback
     * @covers IcecastAuth\IcecastAuth::setAuthErrorCallback
     * @covers IcecastAuth\IcecastAuth::setAddListenerCallback
     * @covers IcecastAuth\IcecastAuth::setRemoveListenerCallback
     * @covers IcecastAuth\IcecastAuth::setAddMountCallback
     * @covers IcecastAuth\IcecastAuth::setRemoveMountCallback
     */
    public function testSetCallbackFunc()
    {
    $setMethods = array('setAuthCallback','setAuthErrorCallback','setAddListenerCallback','setRemoveListenerCallback','setAddMountCallback','setRemoveMountCallback');
    
        foreach ($setMethods as $method) {
            $iceAuth = new IcecastAuth();
            $this->assertTrue($iceAuth->$method('testFunc'),$method.'-func-OK : '.$iceAuth->getLastError());
            $this->assertTrue($iceAuth->$method(array( new testClass(),'testFunc')),$method.'-method-OK : '.$iceAuth->getLastError());
            $this->assertFalse($iceAuth->$method('test'),$method.'-func-NOK : '.$iceAuth->getLastError());
            $this->assertFalse($iceAuth->$method(array( new testClass(),'test')),$method.'-method-NOK : '.$iceAuth->getLastError());
        }
    }
    
    
    /**
     * @covers IcecastAuth\IcecastAuth::execute
     * @runInSeparateProcess
     */
    public function testExecuteAddAuthlistener(){
        $_POST = array(
            'action' => 'listener_add',
            'server' => 'www.test.fr',
            'port' => 8000,
            'client' => 397154,
            'mount' => '/test.mp3?test=test',
            'user' => '',
            'pass' => '',
            'ip' => '1.1.1.1',
            'duration' => 33,
            'sent' => 68805
        );
        
        $iceAuth = new IcecastAuth();
        $mock = $this->getMock('stdClass',array('testCallback'));
        $mock->expects($this->any())
                ->method('testCallback1')
                ->with($this->logicalAnd(
                        $this->arrayHasKey('test'),
                        $this->arrayHasKey('mountpoint'),
                        $this->arrayHasKey('port')
                        ))
                ->will($this->returnValue(true));
        $mock->expects($this->any())
                ->method('testCallback2')
                ->with($this->logicalAnd(
                        $this->arrayHasKey('test'),
                        $this->arrayHasKey('mountpoint'),
                        $this->arrayHasKey('port')
                        ))
                ->will($this->returnValue(true));
                
        $iceAuth->setAuthCallback(array($mock,'testCallback1'));
        $iceAuth->setAddListenerCallback(array($mock,'testCallback2'));
        $iceAuth->execute();
    }
    
    
    /**
     * @covers IcecastAuth\IcecastAuth::execute
     */
    public function testExecuteRemovelistener(){
        $_POST = array(
            'action' => 'listener_remove',
            'server' => 'www.test.fr',
            'port' => 8000,
            'client' => 397154,
            'mount' => '/test.mp3?test=test',
            'user' => '',
            'pass' => '',
            'ip' => '1.1.1.1',
            'duration' => 33,
            'sent' => 68805
        );
        
        $iceAuth = new IcecastAuth();
        $mock = $this->getMock('stdClass',array('testCallback'));
        $mock->expects($this->once())
                ->method('testCallback')
                ->with($this->logicalAnd(
                        $this->arrayHasKey('test'),
                        $this->arrayHasKey('mountpoint'),
                        $this->arrayHasKey('port')
                        ))
                ->will($this->returnValue(true));
                
        $iceAuth->setRemoveListenerCallback(array($mock,'testCallback'));
        $iceAuth->execute();
        
    }
    
    /**
     * @covers IcecastAuth\IcecastAuth::execute
     */
    public function testExecuteAddMount(){
        $_POST = array(
            'action' => 'mount_add',
            'mount' => '/test.mp3',
            'server' => 'www.test.fr',
            'port' => 8000
        );
        
        $iceAuth = new IcecastAuth();
        $mock = $this->getMock('stdClass',array('testCallback'));
        $mock->expects($this->once())
                ->method('testCallback')
                ->with($this->arrayHasKey('port'))
                ->will($this->returnValue(true));
        $iceAuth->setAddMountCallback(array($mock,'testCallback'));
        $iceAuth->execute();
        
    }
    
    /**
     * @covers IcecastAuth\IcecastAuth::execute
     */
    public function testExecuteRemoveMount(){
        $_POST = array(
            'action' => 'mount_remove',
            'mount' => '/test.mp3',
            'server' => 'www.test.fr',
            'port' => 8000
        );
        
        $iceAuth = new IcecastAuth();
        $mock = $this->getMock('stdClass',array('testCallback'));
        $mock->expects($this->once())
                ->method('testCallback')
                ->with($this->arrayHasKey('port'))
                ->will($this->returnValue(true));
        $iceAuth->setRemoveMountCallback(array($mock,'testCallback'));
        $iceAuth->execute();
        
    }
    
    
    /**
     * @covers IcecastAuth\IcecastAuth::altOnError
     * @covers IcecastAuth\IcecastAuth::getLastError
     */
    public function testErrors(){
        $iceAuth = new IcecastAuth();
        $this->assertFalse($iceAuth->setAddListenerCallback('test'));
        $this->assertEquals($iceAuth->getLastError(),'test : Unknown function');
   
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
