<?php
namespace wcf\data\modification\log;

/**
 * Represents a list of viewable modification logs.
 * 
 * @author	Joshua Rüsweg
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	be.bastelstu.josh.globalmodificationlog
 */
class ViewableModificationLogList extends ModificationLogList {
	
	/**
	 * @see	\wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wcf\data\modification\log\ViewableModificationLog';
	
	/**
	 * a list with known object types
	 * 
	 * @var array<int> 
	 */
	public $knownObjectTypes = array(); 
	
	public function __construct() {
		parent::__construct();
		
		// add wbb 
		$this->knownObjectTypes[] = \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.thread');
		$this->knownObjectTypes[] = \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.post');
		
		\wcf\system\event\EventHandler::getInstance()->fireAction($this, 'construct');
		
		$this->conditionBuilder->add('objectTypeID IN (?)', array($this->knownObjectTypes)); 
	}
	
	/**
	 * @see \wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		parent::readObjects();
		
		\wcf\system\event\EventHandler::getInstance()->fireAction($this, 'afterReadObjects'); 
		
		// cache some data
		$userIDs = array(); 
		$objectCache = array(); 
		
		foreach ($this->objects as $object) {
			$userIDs[] = $object->userID; 
			if (!isset($objectCache[$object->objectTypeID])) {
				$objectCache[$object->objectTypeID] = array(); 
			}
			$objectCache[$object->objectTypeID][] = $object->objectID; 
		}
		
		array_unique($userIDs); 
		
		\wcf\data\user\UserProfile::getUserProfiles($userIDs);
		
		foreach ($objectCache as $objectTypeID => $objectIDs) {
			array_unique($objectIDs); 
			
			if (count($objectIDs)) {
				switch ($objectTypeID) {
					case \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.thread'):
						$list = new \wbb\data\thread\ThreadList(); 
						$list->getConditionBuilder()->add('threadID IN (?)', array($objectIDs)); 
						$list->readObjects();
						$list = $list->getObjects(); 
						
						$emptyObject = new \wbb\data\thread\Thread(null, array());
						break; 

					case \wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', 'com.woltlab.wbb.post'): 
						$list = new \wbb\data\post\PostList(); 
						$list->getConditionBuilder()->add('postID IN (?)', array($objectIDs)); 
						$list->readObjects();
						$list = $list->getObjects(); 
						
						$emptyObject = new \wbb\data\post\Post(null, array()); 
						break; 
				}
				
				foreach ($this->objects as &$object) {
					if ($object->objectTypeID == $objectTypeID) {
						if (isset($list[$object->objectID])) {
							$object->object = $list[$object->objectID]; 
						} else {
							$object->object = $emptyObject; 
						}
					}
				}
			}
		}
	}
}