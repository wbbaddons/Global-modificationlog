<?php
namespace wcf\data\modification\log;

/**
 * Represents a viewable modification log entry. 
 * 
 * @author	Joshua Rüsweg
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	be.bastelstu.josh.globalmodificationlog
 */
class ViewableModificationLog extends ModificationLog {
	
	/**
	 * the string for the __toString() return
	 * 
	 * @var string 
	 */
	public $toString = null;
	
	/**
	 * the object for this entry
	 * 
	 * @var mixed 
	 */
	public $object = null; 
	
	/**
	 * try to make this object to an string
	 */
	public function __toString() {
		if ($this->toString === null) {
			switch ($this->objectTypeID) {
				case \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.thread'):
					$this->toString = \wcf\system\WCF::getLanguage()->getDynamicVariable('wcf.acp.modification.log.wbb.thread.'.$this->action, array(
						'thread' => $this->getObject(), 
						'additionalData' => $this->additionalData
					)); 
					break; 
				
				case \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.post'): 
					$this->toString = \wcf\system\WCF::getLanguage()->getDynamicVariable('wcf.acp.modification.log.wbb.post.'.$this->action, array(
						'post' => $this->getObject(), 
						'additionalData' => $this->additionalData
					)); 
					break; 
			}
			
			\wcf\system\event\EventHandler::getInstance()->fireAction($this, 'toString_'. \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectType($this->objectTypeID)->objectType);
		}
		
		if ($this->toString !== null) {
			return $this->toString; 
		}
		
		return '';
	}
	
	/**
	 * get the object for this entry
	 * 
	 * @return mixed
	 */
	public function getObject() {
		if (!$this->object) {
			switch ($this->objectTypeID) {
				case \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.thread'):
					$this->object = new \wbb\data\thread\Thread($this->objectID); 
					break; 
				
				case \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.post'): 
					$this->object = new \wbb\data\post\Post($this->objectID); 
					break; 
			}
			
			\wcf\system\event\EventHandler::getInstance()->fireAction($this, 'getObject_'. \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectType($this->objectTypeID)->objectType);
		}
		
		return $this->object; 
	}
	
	/**
	 * get the user profile for the entry "author"
	 * 
	 * @return \wcf\data\user\UserProfile
	 */
	public function getUserProfile() {
		if ($this->userID == 0 || \wcf\data\user\UserProfile::getUserProfile($this->userID) === null) {
			return new \wcf\data\user\UserProfile(new \wcf\data\user\User(null, array())); 
		}
		
		return \wcf\data\user\UserProfile::getUserProfile($this->userID);
	}
}