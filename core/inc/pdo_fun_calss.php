<?php

//-- 資料庫pdo連線Class(PDO 查詢、新增、修改、刪除) --

class PDO_fun{
    
	//-- 一頁式網站資料庫 --
    private $_dbhost = DB_HOST; //資料庫位置
    private $_dbport = DB_PORT; //資料庫Port
    private $_user_id = DB_USER; //使用者ID
    private $_user_pwd = DB_PWD; //使用者密碼
    private $_dbname = DB_NAME; //資料庫名稱
	
	//-- 短網址資料庫 --
	private $Short_dbhost = SHORT_DB_HOST; //資料庫位置
    private $Short_dbport = SHORT_DB_PORT; //資料庫Port
    private $Short_user_id = SHORT_DB_USER; //使用者ID
    private $Short_user_pwd = SHORT_DB_PWD; //使用者密碼
    private $Short_dbname = SHORT_DB_NAME; //資料庫名稱
    
    public $pdo_obj; //PDO物件
    public $tb_name; //新增、修改、刪除 資料表名稱

    // 歷史紀錄用
    private $h_snapshot; //歷史紀錄
	private $_history_dbname = DB_HIS_NAME; //歷史資料庫名稱
    private $col_old_arr=[];
	private $col_new_arr=[];

	public $hs_tb_name; 
	public $hs_old_index_name='Tb_index'; 
	public $hs_old_id=''; 
	public $hs_new_param; 
	public $hs_h_location; 
	public $hs_h_action_type; 
	public $hs_h_title; 
    public $hs_admin_id=''; 
    public $hs_admin_name=''; 

    
    //-- 建構子 --
    function __construct($db_type = 'website')
    {

        switch ($db_type)
		{
			case 'website':
				$this->_dbname=DB_NAME;
				break;
			case 'short':
				$this->_dbname=SHORT_DB_NAME;
				break;
            case 'history':
				$this->_dbname=DB_HIS_NAME;
				break;
			default:
                $this->_dbname=DB_NAME;
				break;
		}
        
         //$this->_pdo_conn($this->_dbname);
         $this->pdo_obj = $this->_pdo_conn($this->_dbname);
    }
    
    //PDO連線(一頁式網站資料庫)
    function _pdo_conn($db_name)
    {    
        try {
            $pdo=new PDO("mysql:host=".$this->_dbhost.";port=".$this->_dbport.";dbname=".$db_name, $this->_user_id, $this->_user_pwd);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的模擬效果
            $pdo->setAttribute(PDO::ATTR_PERSISTENT, true); //是否持久化連接(以防Connect過多, 解決timewait數量增大)
            $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); //使用緩沖查詢)
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // 修改預設的錯誤顯示級別
            $pdo->exec("SET NAMES UTF8");
            return $pdo;
          } catch (PDOException $e) {
            return '資料庫連線錯誤 : ' . $e->getMessage();
            exit;
          }    
    }
	
	//PDO連線(短網址資料庫)
    function short_pdo_conn()
    {
        $this->pdo_obj = new PDO("mysql:host=".$this->Short_dbhost.";port=".$this->Short_dbport.";dbname=".$this->Short_dbname, $this->Short_user_id, $this->Short_user_pwd);
        $this->pdo_obj->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的模擬效果
        $this->pdo_obj->setAttribute(PDO::ATTR_PERSISTENT, true); //是否持久化連接(以防Connect過多, 解決timewait數量增大)
        $this->pdo_obj->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); //使用緩沖查詢)
        $this->pdo_obj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // 修改預設的錯誤顯示級別
        $this->pdo_obj->exec("SET NAMES UTF8");        
    }
    
    //-- PDO 查詢 --
    function select($sql_query, $where = 'no' ,$fetch_num = 'all', $pdo='pdo_obj')
    {
        $pdo_obj=$pdo=='pdo_obj' ? $this->pdo_obj : $pdo;

        try {
            
            $sql = $pdo_obj->prepare($sql_query);
            
            if ($sql)
            {
                if ($where  !=  'no')
                {
                    $where_key = array_keys($where);//陣列鍵名
                    for ($i=0; $i <count($where) ; $i++)
                    {
                        $sql->bindparam($where_key[$i], $where[$where_key[$i]]);
                    }
                }
                
                $result = $sql->execute();
                $row = '';
                
                if ($result)
                {
                    $row = ($fetch_num == 'one') ? $sql->fetch(PDO::FETCH_ASSOC) : $sql->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch(PDOException $e) {
            
            $row = '';
            
        }
        return $row;
    }
    
    //-- PDO新增 --
    function insert($tb_name, $array_data, $pdo='pdo_obj' )
    {
        $pdo_obj=$pdo=='pdo_obj' ? $this->pdo_obj : $pdo;
        
        $key = array_keys($array_data); //陣列鍵名
        $data_name = '';
        $data = '';
        
        for ($i=0; $i < count($array_data) ; $i++) 
        {
            $data_name .= ($i == count($array_data) - 1) ? $key[$i] : $key[$i].',';
            $data .= ($i == count($array_data) - 1) ? ':'.$key[$i] : ':'.$key[$i].',';
        }
        
        $sql_query = "INSERT INTO ".$tb_name." (".$data_name.") VALUES (".$data.")";
        
        $sql = $pdo_obj->prepare($sql_query);
        
        for ($i=0; $i < count($array_data) ; $i++)
        {
            $sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
        }
        
        $sql->execute();
        
        
    }
    
    //-- 新增資料且取得目前新增之id(自動編號) --
    function insertid($tb_name, $array_data)
    {
        $key = array_keys($array_data); //陣列鍵名
        $data_name = '';
        $data = '';
        
        for ($i=0; $i < count($array_data) ; $i++) 
        {
            $data_name .= ($i == count($array_data) - 1) ? $key[$i] : $key[$i].',';
            $data .= ($i == count($array_data) - 1) ? ':'.$key[$i] : ':'.$key[$i].',';
        }
        
        $sql_query="INSERT INTO ".$tb_name." (".$data_name.") VALUES (".$data.")";
        $sql = $this->pdo_obj->prepare($sql_query);
        
        for ($i=0; $i < count($array_data) ; $i++)
        {
            $sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
        }
        
        $sql->execute();
        
        $last_id = $this->pdo_obj->lastInsertId();
        
        return $last_id;
    }
    
    //-- PDO修改 --
    function update($tb_name, $array_data, $where)
    {
        $key = array_keys($array_data);//陣列鍵名
        $where_key = array_keys($where);
        $data = '';
        $where_sql = '';
        
        for ($i=0; $i < count($array_data) ; $i++)
        {
            $data .= ($i==count($array_data)-1) ? $key[$i].'=:'.$key[$i] : $key[$i].'=:'.$key[$i].',';
        }
        
        for ($i=0; $i < count($where) ; $i++)
        {
            $where_sql .= ($i==count($where)-1) ? $where_key[$i].'=:'.$where_key[$i] : $where_key[$i].'=:'.$where_key[$i].' AND ';
        }
        
        
        
        $sql_query = "UPDATE ".$tb_name." SET ".$data." WHERE ".$where_sql;
        $sql = $this->pdo_obj->prepare($sql_query);
        
        for ($i=0; $i < count($array_data) ; $i++)
        {
            $sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
        }
        
        for ($i=0; $i < count($where) ; $i++)
        {
            $sql->bindparam(':'.$where_key[$i], $where[$where_key[$i]]);
        }
        
        $sql->execute();
    }
    
    //-- PDO刪除 --
    function delete($tb_name, $where)
    {
        $where_key = array_keys($where);//陣列鍵名
        
        $sql_query = "DELETE FROM ".$tb_name." WHERE ".$where_key[0]."=:".$where_key[0];
        
        $sql = $this->pdo_obj->prepare($sql_query);
        $sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
        $sql->execute();
    }

    //-- PDO刪除 多查詢 --
    function delete_more($tb_name, $where)
    {
        $where_key = array_keys($where);//陣列鍵名
        $where_sql='';

        for ($i=0; $i < count($where) ; $i++)
        {
            $where_sql .= ($i==count($where)-1) ? $where_key[$i].'=:'.$where_key[$i] : $where_key[$i].'=:'.$where_key[$i].' AND ';
        }
        
        $sql_query = "DELETE FROM ".$tb_name." WHERE ".$where_sql;
        $sql = $this->pdo_obj->prepare($sql_query);

        for ($i=0; $i < count($where) ; $i++)
        {
            $sql->bindparam(':'.$where_key[$i], $where[$where_key[$i]]);
        }
       //$sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
        $sql->execute();
    }



    /* ---------------- 新增紀錄 ----------------- */
    function add_history($db_name='website')
    {
        $_pdo=$db_name=='website' ? 'pdo_obj' : $this->_pdo_conn($this->Short_dbname);
		//-- 歷史紀錄 --
		
		//-- 新資料 --
		$tb_name=$this->hs_tb_name;
		$old_index_name=$this->hs_old_index_name;
		$old_id=$this->hs_old_id;
		//-- 新資料 --
		if($old_id==''){
			$data=$this->hs_new_param;
		}
		else{
			$data=$this->select("SELECT * FROM $tb_name WHERE $old_index_name=:$old_index_name", [$old_index_name=>$old_id], 'one', $_pdo);
		}

		$col=$this->select("SHOW FULL COLUMNS FROM $tb_name", 'no', 'all', $_pdo);
		foreach ($col as $one) {
			$this->col_new_arr[$one['Field']]=empty($data[$one['Field']]) ? '':$data[$one['Field']];
		}
		
        

		$this->h_snapshot=[
			'old'=>$this->col_old_arr,
			'new'=>$this->col_new_arr
		];
		

		$pdo_history=$this->_pdo_conn($this->_history_dbname);

        $admin_id=empty($this->hs_admin_id) ? $_COOKIE['admin_index']:$this->hs_admin_id;
        $admin_name=empty($this->hs_admin_name) ? $_COOKIE['admin_name']:$this->hs_admin_name;
		
        $h_param=[
            'admin_id'=>$admin_id,
            'admin_name'=>$admin_name,
            'h_location'=>$this->hs_h_location,
            'h_action_type'=>$this->hs_h_action_type,
            'h_title'=>$this->hs_h_title,
            'h_snapshot'=> json_encode($this->h_snapshot),
            'StartDate'=>date('Y-m-d H:i:s')
        ];
        $this->insert('sysHistory', $h_param, $pdo_history);
		$this->close($pdo_history);
    }

	/* ---------------- 歷史紀錄(舊資料) ----------------- */
	function old_data($db_name='website')
	{
        $_pdo=$db_name=='website' ? 'pdo_obj' : $this->_pdo_conn($this->Short_dbname);

		$tb_name=$this->hs_tb_name;
		$old_index_name=$this->hs_old_index_name;
		$old_id=$this->hs_old_id;
		//-- 舊資料 --
		$data=$this->select("SELECT * FROM $tb_name WHERE $old_index_name=:$old_index_name", [$old_index_name=>$old_id], 'one', $_pdo);
		$col=$this->select("SHOW FULL COLUMNS FROM $tb_name", 'no', 'all', $_pdo);
		foreach ($col as $one) {
            $this->col_old_arr[$one['Field']]=empty($data[$one['Field']]) ? '':$data[$one['Field']];
        }

		return $data;
	}



    
    //-- 關閉PDO --
    function close($pdo='pdo_obj')
    {
        if($pdo=='pdo_obj'){
            $this->pdo_obj=NULL;
        }
        else{
            $pdo=NULL;
        }
    }
    
}
?>