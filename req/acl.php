<?php
namespace App;
@session_start();
class Acl {
	private $database;
	private $tagId;
	private $groupNamesLookupTable = array(
		1 => "admin",
		2 => "dealer",
		3 => "agent"
	);

	function __construct($database) {
		$this->database = $database;
	}

	public function tag($name) {

		//Create tag if it doesn't exist already
		if(!$this->tagExists($name)) {
			$this->createTag($name);
			return false;
		}

		//if this user has a group
		if($this->getGroupId()) {
			$rule = $this->getRules($this->getGroupId(), $this->tagId);
			//be sure the isn't mulitple rules
			if(!is_array($rule)) {
				//if the rule calls for denying the request
				if($rule->action === 'deny') {
					$this->denied();
				}else{
					return;
				}
			}
		}
	}

	public function isAuthed() {
		if($_SESSION['employeeId']) {
			return true;
		}else{
			return false;
		}
	}

	public function inGroup($groupName) {

		$actualGroupName = $this->getGroupName();

		if(!is_array($groupName)) {			
			$ret = ($actualGroupName === $groupName ? true : false);
		}else{
			$ret = (in_array($actualGroupName, $groupName) ? true : false);
		}

		return $ret;
	}

	public function getLoginName() {
		return $_SESSION['employeeId'];
	}

	public function getDealerIds() {
		$id = $_SESSION['employeeId'];

		$query = "SELECT dealer_id FROM logins WHERE login_id = '$id'";
		$result = $this->database->query($query);
		$dealerIdString = $result->dealer_id;

		$dealerIdArray = explode(',', $dealerIdString);

		return $dealerIdArray;
	}

	private function lookupGroupId($groupId) {
		return $this->groupNamesLookupTable[$groupId];
	}

	public function getGroupName() {
		$id = $this->getGroupId();
		$name = $this->lookupGroupId($id);

		return $name;
	}

	private function getGroupId() {
		if($_SESSION['groupId']) {
			return $_SESSION['groupId'];
		}else{
			return false;
		}
	}

	private function getCallingScriptName() {
		return $_SERVER['SCRIPT_FILENAME'];
	}

	private function getRules($groupId, $tagId) {
		$query = "SELECT * FROM acl_group_rules WHERE group_id = '$groupId' and tag_id = '$tagId'";
		$result = $this->database->query($query);

		if(!$result) {
			return false;
		}else{
			return $result;
		}
	}

	private function tagExists($name) {
		$existsQuery = "SELECT * FROM acl_tags WHERE name = '$name'";
		$result = $this->database->query($existsQuery);

		if(!$result) {
			return false;
		}else{
			$this->tagId = $result->id;
			return true;
		}
	}

	private function createTag($name) {
		$script = $this->getCallingScriptName();
		$createQuery = "INSERT INTO acl_tags (name, script) VALUES('$name', '$script')";
		$this->database->query($createQuery);

		$tagId = $this->database->lastInsertId;

		for($i = 1; $i <= 3; $i++) {
			$query = "INSERT INTO acl_group_rules (group_id, tag_id, action) VALUES('$i', '$tagId', 'allow')";
			$this->database->query($query);
		}

		return true;
	}

	private function denied() {
		header("Location: denied.php");
		return true;
	}
}
