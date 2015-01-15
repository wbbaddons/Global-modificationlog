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
	
	/**
	 * see \wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'time';
	
	/**
	 * see \wcf\page\SortablePage::$defaultSortOrder
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * see \wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('time');
	
	// Filter:
	public $userIDs = array();
	public $usernames = array();
	public $objectType = null; 
	public $startDate = null; 
	public $endDate = null; 
	public $action = null; 
	
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
		if (isset($_REQUEST['action'])) $this->action = $_REQUEST['action']; 
		if (isset($_REQUEST['startDate'])) $this->startDate = $_REQUEST['startDate'];
		if (isset($_REQUEST['endDate'])) $this->endDate = $_REQUEST['endDate'];
		
		$this->validate(); 
	}
	
	/**
	 * validates the given filters
	 */
	public function validate() {
		if (!empty($this->objectType) && !in_array($this->objectType, $this->valideObjectTypes)) {
			$this->objectType = null; 
		}
		
		if ($this->action == 'all') {
			$this->action = null; 
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
		
		if (@strtotime($this->startDate) === false) {
			$this->startDate = null; 
		}
		
		if (@strtotime($this->endDate) === false) {
			$this->endDate = null; 
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
		
		if (!empty($this->action)) {
			$this->objectList->getConditionBuilder()->add('action = ?', array($this->action));
		}
		
		if (!empty($this->startDate)) {
			$this->objectList->getConditionBuilder()->add('time > ?', array(strtotime($this->startDate)));
		}
		
		if (!empty($this->endDate)) {
			$this->objectList->getConditionBuilder()->add('time < ?', array(strtotime($this->endDate)));
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
		
		if (!empty($this->action)) {
			$this->urlGETFilter .= '&action='. \wcf\util\StringUtil::encodeHTML($this->action);
		}
		
		if (!empty($this->startDate)) {
			$this->urlGETFilter .= '&startDate='. \wcf\util\StringUtil::encodeHTML($this->startDate);
		}
		
		if (!empty($this->endDate)) {
			$this->urlGETFilter .= '&endDate='. \wcf\util\StringUtil::encodeHTML($this->endDate);
		}
		
		\wcf\system\WCF::getTPL()->assign(array(
			'usernames' => $this->usernames, 
			'objectType' => $this->objectType, 
			'filter' => $this->urlGETFilter,
			'startDate' => $this->startDate,
			'endDate' => $this->endDate,
			'action' => $this->action
		));
	}
}