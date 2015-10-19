<?php
/*
 * This file is part of the IceCastAuth package.
 *
 * (c) Gregory Brousse <pro@gregory-brousse.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace IcecastAuth;


/**
 * IcecastAuht class
 *
 * manage listener authentification and events send by icecast server.
 *
 * @author Gregory Brousse <pro@gregory-brousse.fr>
 */
class IcecastAuth {
    /**
     * function or object/method used for the authentification
     * @var callable[]
     */
    private $authFunction = '';
    
    /**
     * function or object/method called on authentification error
     * @var callable[]
     */
    private $authErrorFunction = '';
    
    /**
     * function or object/method called when a listener is added on a mount
     * @var callable[]
     */
    private $addListenerFunction = '';
    
    /**
     * function or object/method called when a listener is removed on a mount
     * @var callable[]
     */
    private $removeListenerFunction = '';
    
    /**
     * function or object/method called when a Mountpoint is added on icecast server
     * @var callable[]
     */
    private $addMountFunction = '';
    
    /**
     * function or object/method called when a Mountpoint is removed on icecast server
     * @var callable[]
     */
    private $removeMountFunction = '';
    
    /**
     * Header send back to icecast when user is aurhenticated, must match with the mount configuration
     * @var string
     */
    private $header = 'icecast-auth-user:1';
    
    /**
     * Last error of this class
     * @var string
     */
    private $lastError = '';
    
    /**
     * Set the callback called on authentification request, this function must accept 1 parameter.
     * This parameter is an array containing the datas sent by icecast. 
     * This function must be set.
     * @param callable[] $function
     * @return boolean
     */
    public function setAuthCallback($function){
        if(!is_callable($function))return $this->altOnError( $function.' : Unknown function');
        $this->authFunction = $function;
        return true;
    }
    
    /**
     * Set the callback called on authentification request error, this function must accept 1 parameter.
     * This parameter is an array containing the datas sent by icecast. 
     * This function is optionnal 
     * @param callable[] $function
     * @return boolean
     */
    public function setAuthErrorCallback($function){
        if(!is_callable($function))return $this->altOnError($function.' : Unknown function');
        $this->authErrorFunction = $function;
    }
    
    /**
     * Set the callback called when a listener is added, this function must accept 1 parameter.
     * This parameter is an array containing the datas sent by icecast. 
     * This function is optionnal 
     * @param callable[] $function
     * @return boolean
     */
    public function setAddListenerCallback($function){
        if(!is_callable($function))return $this->altOnError($function.' : Unknown function');
        $this->addListenerFunction = $function;
    }

    /**
     * Set the callback called when a listener is removed , this function must accept 1 parameter.
     * This parameter is an array containing the datas sent by icecast. 
     * This function is optionnal 
     * @param callable[] $function
     * @return boolean
     */
    public function setRemoveListenerCallback($function){
        if(!is_callable($function))return $this->altOnError($function.' : Unknown function');
        $this->removeListenerFunction = $function;
    }
    
    /**
     * Set the callback called when a mount is added, this function must accept 1 parameter.
     * This parameter is an array containing the datas sent by icecast. 
     * This function is optionnal 
     * @param callable[] $function
     * @return boolean
     */
    public function setAddMountCallback($function){
        if(!is_callable($function))return $this->altOnError($function.' : Unknown function');
        $this->addMountFunction = $function;
    }
    
    /**
     * Set the callback called when a mount is removed, this function must accept 1 parameter.
     * This parameter is an array containing the datas sent by icecast. 
     * This function is optionnal 
     * @param callable[] $function
     * @return boolean
     */
    public function setRemoveMountCallback($function){
        if(!is_callable($function))return $this->altOnError($function.' : Unknown function');
        $this->removeMountFunction = $function;
    }
    
    /**
     * Set the header param sent with HTTP response when a user is authenticate
     * The parameter must match with the "auth_header" option in the icecast mount configuration
     * @param string $authHeader
     */
    public function setAuthHeader($authHeader){
        if($authHeader != '')$this->header = $authHeader;
    }
    
    /**
     * Get icecast datas and launch callbacks matching with the request
     * Use this method after the configuration of the object
     */
    public function execute(){
        // Get datas sent with the mount url
        $parameters = array();
        $parsedUrl = parse_url($_POST['mount']);
        parse_str($parsedUrl['query'], $parameters);
        $parameters['mountpoint'] = $parsedUrl['path'];
        // Merge these datas with POST datas sent by icecast
        $parameters = array_merge($parameters,$_POST);


        switch($parameters['action']){
            case 'listener_add': //add listener event
                // try to authenticate the new listener
                if($this->call($this->authFunction,$parameters)){
                    // set the header for an authenticate listener
                    header($this->header);
                    // call the add listener callback
                    $this->call($this->addListenerFunction,$parameters);
                }else{
                    // set the header for an non authenticate listener
                    $errorMessage = 'Access refused';
                    if(isset($parameters['error']))$errorMessage = $parameters['error'];
                    header('icecast-auth-message:'.$errorMessage);
                    // call the authentification error callback
                    $this->call($this->authErrorFunction,$parameters);
                }
                break;
            case 'listener_remove': //remove listener event
                // call the callback
                $this->call($this->removeListenerFunction,$parameters);
                break;
            case 'mount_add': //add mount event
                // call the callback
                $this->call($this->addMountFunction,$parameters);
                break;
            case 'mount_remove': //remove mount event
                // call the callback
                $this->call($this->removeMountFunction,$parameters);
                break;
        }
    }
    
    /**
     * Call a callback
     * @return boolean the return of the called callback
     */
    private function call($function,$parameters){
        if($function == '')return false;
        return call_user_func($function,$parameters);
    }
    
    /**
     * Set the error and return false
     * @param string $error
     * @return boolean
     */
    private function altOnError($error){
        $this->lastError = $error;
        return false;
    }
    
    /**
     * @return string last error
     */
    public function getLastError(){
        return $this->lastError;
    }
}
?>