<?php
namespace wcf\acp\page; 

/**
 * Represents a viewable modification log list page. 
 * 
 * @author	Joshua Rüsweg
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	be.bastelstu.josh.globalmodificationlog
 */
class GlobalModificationLogListPage extends \wcf\page\SortablePage {
	/**
	 * @see \wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wcf\data\modification\log\ViewableModificationLogList';
	
	/**
	 * @see \wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.system.canViewLog');
	
	// Filter:
	public $userIDs = array();
	public $usernames = array();
	public $objectType = null; 

	/**
	 * valid objectTypes 
	 * 
	 * @var array<String>
	 */
	public $valideObjectTypes = array(
		'com.woltlab.wbb.post', 
		'com.woltlab.wbb.thread'
	); 

	/**
	 * @see \wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.log.modification'; 
	
	/**
	 * the filter string for the url
	 * @var String
	 */
	public $urlGETFilter = '';
	
	/**
	 * @see	\wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['username'])) {
			$this->usernames = explode(',', $_REQUEST['username']); 
			$this->usernames = \wcf\util\ArrayUtil::trim($this->usernames); 
		}
		
		if (isset($_REQUEST['objectType'])) $this->objectType = \wcf\util\StringUtil::trim($_REQUEST['objectType']);
		
		$this->validate(); 
	}
	
	/**
	 * validates the given filters
	 */
	public function validate() {
		if (!empty($this->objectType) && !in_array($this->objectType, $this->valideObjectTypes)) {
			$this->objectType = null; 
		}
		
		if (count($this->usernames)) {
			$userList = new \wcf\data\user\UserList(); 
			$userList->getConditionBuilder()->add('username IN (?)', array($this->usernames)); 
			$userList->readObjects(); 
			
			$this->usernames = array(); // reset usernames
			foreach ($userList->getObjects() as $user) {
				$this->userIDs[] = $user->getObjectID(); 
				$this->usernames[] = $user->username; 
			}
		}
	}
	
	/**
	 * @see	\wcf\page\MultipleLinkPage::initObjectList()
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		if (!empty($this->userIDs)) {
			$this->objectList->getConditionBuilder()->add('userID IN (?)', array($this->userIDs));
		}
		
		if (!empty($this->objectType)) {
			$this->objectList->getConditionBuilder()->add('objectTypeID = ?', array(\wcf\data\object\type\ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.modifiableContent', $this->objectType)));
		}
	}
	
	/**
	 * @see	\wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		if (!empty($this->usernames)) {
			$this->urlGETFilter .= '&username='. \wcf\util\StringUtil::encodeHTML(implode(',', $this->usernames)); 
		}

		if (!empty($this->objectType)) {
			$this->urlGETFilter .= '&objectType='. \wcf\util\StringUtil::encodeHTML($this->objectType); 
		}
		
		\wcf\system\WCF::getTPL()->assign(array(
			'usernames' => $this->usernames, 
			'objectType' => $this->objectType, 
			'filter' => $this->urlGETFilter 
		));
	}
}