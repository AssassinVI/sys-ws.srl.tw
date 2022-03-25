<?php
 
 /**
  * PDO 查詢、新增、修改、刪除
  */
 class PDO_fun
 {
 	
 	private $_dbname = 'srltw_test_case'; //資料庫名稱
	private $_user_id = 'srltw_website'; //使用者ID
	private $_user_pwd = '1qazXSW@3'; //使用者密碼
	
	public $pdo_obj; //PDO物件
    public $tb_name; //新增、修改、刪除 資料表名稱

    
    //-- 建構子 --
	function __construct()
	{
		$this->_pdo_conn();
	}

	/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ PDO連線 @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */
 	 function _pdo_conn() {
		$this->pdo_obj = new PDO("mysql:host=localhost;dbname=".$this->_dbname, $this->_user_id, $this->_user_pwd);
		$this->pdo_obj->exec("SET NAMES UTF8");
	}


	/* ----------------------- PDO 查詢 --------------------------- */
	function select($sql_query, $where='no' ,$fetch_num='all')
	{
	  
	  $sql=$this->pdo_obj->prepare($sql_query);

	  if ($where!='no') {
	     $where_key=array_keys($where);//陣列鍵名
	     for ($i=0; $i <count($where) ; $i++) { 
	        $sql->bindparam($where_key[$i], $where[$where_key[$i]]);
	     }
	  }
	  $sql->execute();
      
      if ($fetch_num=='one') {
      	$row=$sql->fetch(PDO::FETCH_ASSOC);
	    return $row;
      }
      else{
      	$row=$sql->fetchAll(PDO::FETCH_ASSOC);
      	return $row;
      }
	     
	}


	/* ---------------- PDO新增 ----------------- */
	function insert($tb_name, $array_data )
	{
	  $key=array_keys($array_data); //陣列鍵名
	  $data_name='';
	  $data='';

	  for ($i=0; $i < count($array_data) ; $i++) { 
	  	if ($i==count($array_data)-1) {
	  	  $data_name.=$key[$i];
	  	  $data.=':'.$key[$i];
	  	}else{
	     $data_name.=$key[$i].',';
	  	  $data.=':'.$key[$i].',';
	  	}
	  }

	  $sql_query="INSERT INTO ".$tb_name." (".$data_name.") VALUES (".$data.")";

		$sql=$this->pdo_obj->prepare($sql_query);
	  for ($i=0; $i < count($array_data) ; $i++) { 
	  		$sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
	  	}	
		$sql->execute();
	}


	/* ---------------- PDO修改 ----------------- */
	function update($tb_name, $array_data, $where)
	{
	  $key=array_keys($array_data);//陣列鍵名
	  $where_key=array_keys($where);
	  $data='';
	  $where_sql='';

	  for ($i=0; $i < count($array_data) ; $i++) { 
	  	if ($i==count($array_data)-1) {
	  	  $data.=$key[$i].'=:'.$key[$i];
	  	}else{
	  	  $data.=$key[$i].'=:'.$key[$i].',';
	  	}
	  }

	  for ($i=0; $i < count($where) ; $i++) { 
	  	if ($i==count($where)-1) {
	  	  $where_sql.=$where_key[$i].'=:'.$where_key[$i];
	  	}else{
	  	  $where_sql.=$where_key[$i].'=:'.$where_key[$i].' AND ';
	  	}
	  }

	  $sql_query="UPDATE ".$tb_name." SET ".$data." WHERE ".$where_sql;

		$sql=$this->pdo_obj->prepare($sql_query);
	  for ($i=0; $i < count($array_data) ; $i++) { 
	  		$sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
	  	}	
	  for ($i=0; $i < count($where) ; $i++) { 
	  		$sql->bindparam(':'.$where_key[$i], $where[$where_key[$i]]);
	  	}

		$sql->execute();
	}


	/* ---------------- PDO刪除 ----------------- */
	function delete($tb_name, $where)
	{
		$where_key=array_keys($where);//陣列鍵名
	   
	   $sql_query="DELETE FROM ".$tb_name." WHERE ".$where_key[0]."=:".$where_key[0];

		$sql=$this->pdo_obj->prepare($sql_query);	
	  	$sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
		$sql->execute();
	}

	/*-- 關閉PDO --*/
	function close()
	{
		$this->pdo_obj=NULL;
	}
 }
?>