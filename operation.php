<meta charset="UTF-8">
<?php
   session_start ();
   //error_reporting(0);
   /*if(!isset($_SESSION['un']))
	{
		echo "请登录!";
	}
	else
	{ */
	   if(isset($_POST['submit']))
	   {
		   $operation=$_POST['submit'];
	   }
	   if(isset($_POST['user']))
	   {
		   $user=$_POST['user'];
	   }
	   if(isset($_POST['value']))
	   {
		   $value=$_POST['value'];
	   }
	   if(isset($_POST['word']))
	   {
		   $word=$_POST['word'];
	   }
	   if(isset($_POST['statistic']))
	   {
	   	   $statistic=$_POST['statistic'];	
	   }
	   function add($type,$num)
               {
	               include("conn.php");
				   mysql_query("BEGIN");
				   //操作message表
				   $query=mysql_query("SELECT * FROM `message` WHERE `user`='$user'");
				   $row=mysql_fetch_array($query);
				   $cal=intval($row[$type])+ 1;
				   $mul=intval($row['total'])- $num;
				   $res1=mysql_query("UPDATE `message` SET `$type`='$cal',`total`='$mul' WHERE `user`='$user'");
				   //操作statistic表
				   $select=mysql_query("SELECT MAX(`id`) FROM `statistic` WHERE `name` ='$user'" );
				   $array=mysql_fetch_array($select);
				   $time=$array['time'];
				   $name=$array['name'];
				   $nowtime=intval(date('m',time()));
			       $sqltime=intval(date('m',$time));
				   $id=$array['id'];
				   $cal2=intval($array[$type])+ 1;
				   if($sqltime==$nowtime)
				   {
					    $res2=mysql_query("UPDATE `statistic` SET `$type`='$cal2' WHERE `name`='$user',`id`='$id'");
				   }
				   else
				   {
		                $res3=mysql_query("INSERT INTO `statistic`(`id`, `name`, `uarrive`, `vacate`, `late`,`total``time`) VALUES ('','$name','0','0','0','0',now())");
				   }
				   //事务判断
				   if($res1 && ($res2 || $res3))
				   {
					   	  mysql_query("COMMIT");
					   	  $row=mysql_fetch_array($query);
			              echo json_encode($row[$type]);
				   }
				   else
				   {
		               mysql_query("ROLLBACK");
		               echo "try agian!";
				   }
				   mysql_query("END");
			}
	   function del($type,$num)
               {
	               include("conn.php");
				   mysql_query("BEGIN");
				   //操作message表
				   $query=mysql_query("SELECT * FROM `message` WHERE `user`='$user'");
				   $row=mysql_fetch_array($query);
				   $cal=intval($row[$type])- 1;
				   $mul=intval($row['total'])+ $num;
				   $res1=mysql_query("UPDATE `message` SET `$type`='$cal',`total`='$mul' WHERE `user`='$user'");
				   //操作statistic表
				   $select=mysql_query("SELECT MAX(`id`) FROM `statistic` WHERE `name` ='$user'" );
				   $array=mysql_fetch_array($select);
				   $time=$array['time'];
				   $name=$array['name'];
				   $nowtime=intval(date('m',time()));
			       $sqltime=intval(date('m',$time));
				   $id=$array['id'];
				   $cal2=intval($array[$type])- 1;
				   if($sqltime==$nowtime)
				   {
					    $res2=mysql_query("UPDATE `statistic` SET `$type`='$cal2' WHERE `name`='$user',`id`='$id'");
				   }
				   else
				   {
		                $res3=mysql_query("INSERT INTO `statistic`(`id`, `name`, `uarrive`, `vacate`, `late`,`total``time`) VALUES ('','$name','0','0','0','0',now())");
				   }
				   //事务判断
				   if($res1 && ($res2 || $res3))
				   {
					   	  mysql_query("COMMIT");
					   	  $row=mysql_fetch_array($query);
			              echo json_encode($row[$type]);
				   }
				   else
				   {
		               mysql_query("ROLLBACK");
		               echo "try agian!";
				   }
				   mysql_query("END");
			}
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   //添加一个新用户
	    if($operation=='add')
	   {
		   include("conn.php");
		   $result= mysql_query("SELECT * FROM `message` WHERE `user`='$user'");
		   $row=mysql_fetch_array($result);
		   if($row)
			{
			   echo "用户名已存在";
		    }
           else
		   {
		   	   mysql_query("BEGIN");
			   $res1=mysql_query("INSERT INTO `message`(`id`, `user`, `uarrive`, `vacate`, `late`,`total`) VALUES ('','$user','0','0','0','0')");
			   $res2=mysql_query("INSERT INTO `statistic`(`id`, `name`, `uarrive`, `vacate`, `late`,`total`,`time`) VALUES ('','$user','0','0','0','0',now())");
			   if($res1 && $res2)
		       {
			       mysql_query("COMMIT");
				   echo "OKadd";
		       }
		       else
		       {
	               mysql_query("ROLLBACK");
	               echo "try agian!";
			   }
			   mysql_query("END");
	       }
	    }
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   //删除一个用户
	    elseif($operation=='del')
	   {
		   include("conn.php");
		   mysql_query("BEGIN");
		   $res1=mysql_query("DELETE  FROM `message` WHERE `user`='$user'");
		   $res2=mysql_query("DELETE  FROM `statistic` WHERE `name`='$user'");
		   if($res1 && $res2)
		       {
			       mysql_query("COMMIT");
				   echo "OKdel";
		       }
		       else
		       {
	               mysql_query("ROLLBACK");
	               echo "try agian!";
			   }
		   mysql_query("END");
	   }
/* -------------------------------------------分割线-----------------------------------------------------------*/
       //更改工作点
	   elseif($operation=='update')
	   {
		   include("conn.php");
		   //mysql_query("BEGIN");
		   //操作message表
		   $query=mysql_query("SELECT * FROM `message` WHERE `user`='$user'");
		   $row=mysql_fetch_array($query);
		   $value=intval($value);
		   $cal1=intval($row['total'])+$value;
		   $res1=mysql_query("UPDATE `message` SET `total`='$cal1' WHERE `user`='$user'");
		   //操作statistic表
		   $select=mysql_query("SELECT * FROM `statistic` WHERE `name` ='$user' order by `id` desc limit 1" );
		   $array=mysql_fetch_array($select);
		   $time=$array['time'];
		   $name=$array['name'];
		   $nowtime=intval(date('m',time()));
		   $sqltime=intval(date('m',$time));
		   $id=$array['id'];
		   $cal2=intval($array['total'])+$value;
		   if($nowtime==$sqltime)
		   {
			    global $res2;
			    $res2=mysql_query("UPDATE `statistic` SET `total`='$cal2' WHERE `name`='$user',`id`='$id'");
		   }
		   else
		   {
                global $res3;
                $res3=mysql_query("INSERT INTO `statistic`(`id`, `name`, `uarrive`, `vacate`, `late`,`total``time`) VALUES ('','$name','0','0','0','0',now())");
		   }
		   //操作history表
		   $un=$_SESSION['un'];
		   $res4=mysql_query("INSERT INTO `history`(`name`,`history`,`time`,`value`,`editor`) VALUES ('$user','$word',now(),'$value','$un)");
		   //事务判断
		   /*if($res1 && ($res2 || $res3) && $res4)
		   {
			   	  mysql_query("COMMIT");
			   	  echo "OKupdate";
		   }
		   else
		   {
               mysql_query("ROLLBACK");
               echo "try agian!";
		   }
		   mysql_query("END");*/
	   }
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   //最高权限调整工作点
	   elseif($operation=='updateadd')
	   {
	   	    include("conn.php");
	   	    if($_SESSION['un']=="黄玮"||"刘思佳"||"旷春燕")
	   	    {
			   	    mysql_query("BEGIN");
			   	    //操作message表
					$query=mysql_query("SELECT * FROM `message` WHERE `user`='$user'");
					$row=mysql_fetch_array($query);
					$cal1=intval($row['total'])+ 0.1;
				    $res1=mysql_query("UPDATE `message` SET `total`='$cal1' WHERE `user`='$user'");
				    //操作statistic表
				    $select=mysql_query("SELECT * FROM `statistic` order by `id` desc WHERE `name` ='$user' limit 1" );
				    $array=mysql_fetch_array($select);
				    $id=$array['id'];
				    $cal2=intval($array['total'])+ 0.1;
				    $res2=mysql_query("UPDATE `statistic` SET `total`='$cal2' WHERE `name`='$user',`id`='$id'");
				    if($res1 && $res2)
				    {
				    	 mysql_query("COMMIT");
				    	 echo "OKupdateadd";
				    }
				    else
				    {
				    	mysql_query("ROLLBACK");
				    	echo "try again!";
				    }
				    mysql_query("END");
			}
			else
			{
                    echo "权限不足";
			}

	   }
	   elseif($operation=='updatedel')
	   {
	   	    include("conn.php");
	   	    if($_SESSION['un']=="黄玮"||"刘思佳"||"旷春燕")
	   	    {
			   	    mysql_query("BEGIN");
			   	    //操作message表
					$query=mysql_query("SELECT * FROM `message` WHERE `user`='$user'");
					$row=mysql_fetch_array($query);
					$cal1=intval($row['total'])- 0.1;
				    $res1=mysql_query("UPDATE `message` SET `total`='$cal1' WHERE `user`='$user'");
				    //操作statistic表
				    $select=mysql_query("SELECT MAX(`id`) FROM `statistic` WHERE `name` ='$user'" );
				    $array=mysql_fetch_array($select);
				    $id=$array['id'];
				    $cal2=intval($array['total'])- 0.1;
				    $res2=mysql_query("UPDATE `statistic` SET `total`='$cal2' WHERE `name`='$user',`id`='$id'");
				    //事务判断
				    if($res1 && $res2)
				    {
				    	 mysql_query("COMMIT");
				    	 echo "OKupdateadd";
				    }
				    else
				    {
				    	mysql_query("ROLLBACK");
				    	echo "try again!";
				    }
				    mysql_query("END");
			}
			else
			{
				    echo "权限不足";
			}
	   }
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   //加减旷到分
	   elseif($operation=='uarriveadd')
		{
		 	  add($type='uarrive',$num='intval("2")'); 
	    }
	   elseif($operation=='uarrivedel')
	   {
			  del($type='uarrive',$num='intval("2")');
	   }
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   //加减请假分
	   elseif($operation=='vacateadd')
	   {
		      add($type='vacate',$num='intval("1")');
	   }
	   elseif($operation=='vacatedel')
	   {
		      del($type='vacate',$num='intval("1")');
	   }
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   //加减迟到分
	   elseif($operation=='lateadd')
	   {
		   add($type='late',$num='intval("0.5")');
	   }
	   elseif($operation=='latedel')
	   {
		   del($type='late',$num='intval("1")');
	   }
/* -------------------------------------------分割线-----------------------------------------------------------*/
	   else
	   {
		   echo "error";
	   }
   //}
?>