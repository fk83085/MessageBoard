<?php 
	$vData = array();
	$vData[0]["Data"]["descriptionlineid"]  = "test1";
	$vData[0]["Data"]["descriotion"]  = "５ｋ４ｇ４";
	$vData[0]["Data"]["datecreated"]  = '2015-11-11 00:00:00';
	$vData[0]["Data"]["userid"]  = "8712364";

	$oDB = new DataBaseIO();
	$oDB->initial ("test", "description");
	$oDB->vDataSet = &$vData;
	//$oDB->initial ("test", "user");
	//$oDB->read(" username = 'fk8826' ");
	if ($oDB->save()) {
		echo "Success";
	}
	else {
		echo "Fail";
	}	
	$oDB->close();
	//var_dump($vData);
	
class DataBaseIO {
	public $oDB;
	public $oDBLink;
	public $vDataSet;
	
	private $Table = "";
	
	
	
	public function initial ($sDBName, $sTableName) {
		$this->oDBLink = mysqli_connect("localhost", "root", "", $sDBName); 
		$this->Table = $sTableName;
		$this->vDataSet = array();
	}
	
	public function read($sSQL, $sField="") {
		if ("" == $sField) {
			$sFetchField = " * ";
		}
		$this->oDB = mysqli_query($this->oDBLink, "SELECT ".$sFetchField." FROM ".$this->Table." WHERE ".$sSQL);
		while ($row = mysqli_fetch_array($this->oDB, MYSQLI_ASSOC)) {
			$this->vDataSet[] = $row;
		}
		//mysqli_free_result($this->oDB);
	}
	
	public function close() {
		$this->oDB->close();
	}
	
	public function save() {
		
		$bRes = true;
		$this->oDB = mysqli_query($this->oDBLink, "SHOW COLUMNS FROM ".$this->Table );

		$vTableField = array();
		while ($row = mysqli_fetch_array($this->oDB, MYSQLI_ASSOC)) {
			$vTableField[] = $row['Field'];
		}
		if (!empty($this->vDataSet)) {
		  foreach($this->vDataSet as $arrData) {
			$sClause = " insert into  ".$this->Table." ";
			$sFieldClause  = "" ;
			$sValueClause = "";
		    if (!empty($vTableField)) {
				foreach($vTableField as $sField) {
					$vField[] = $sField;
					$vValue[] = $arrData["Data"][$sField];
				}
			}
			$sClause .=  " ( ".implode(",", $vField)." )  VALUES ( '".implode("','",$vValue )."' ) ";
			echo $sClause;
			if (!mysqli_query($this->oDBLink, $sClause)) {
				$bRes = false;
			}
		  }
		}
		return $bRes;
	}
}
?>