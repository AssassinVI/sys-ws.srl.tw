<?php
/*------ 串接VIEW -------*/

class view_class
{
  
    public $view_html;

    //-- 建構子 --
	function __construct($path)
	{
		$this->view_html=file_get_contents($path);
	}

    /*-- 更改參數 --*/
	function replace($search ,$replace)
	{
		$this->view_html=str_replace($search, $replace, $this->view_html);
	}

    /*-- 輸出 --*/
	function output()
	{
	   return $this->view_html;
	}
}

?>