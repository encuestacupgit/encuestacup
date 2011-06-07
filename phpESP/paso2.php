<?php

$host="localhost"; // Host name
$username="root"; // Mysql username
$password=""; // Mysql password
$db_name="phpesp"; // Database name
$conn = mysql_connect("$host", "$username", "$password")or die("No se puede conectar");
mysql_select_db("$db_name")or die("Imposible Conectar a la base de datos");


$data 		= 	explode('|', $_POST['radData']);
$periodo 	= 	$data[0];//$_POST ['materias'];
$materia 	=	$data[1];//$_POST['quecurso'];
$curso 	=	$data[2];//$_POST['quecurso'];
$leg		=	$_POST['userid'];

//me fijo si ya hizo la encuesta o msi existe la encuesta
$sql="SELECT survey_id 
		FROM cup_survey 
		WHERE periodo_curso	=	'$periodo' 
		AND nro_materia	=	'$materia'
		AND nro_curso_id		=	'$curso'";
//echo $sql;exit;
$result = mysql_query($sql);

if($result)
{
	while ($fila2 = mysql_fetch_array($result))
	{
		$sid=$fila2[0];
	}
}


$sql2="SELECT * 
		FROM phpesp_response 
		WHERE survey_id='$sid' 
		AND username='$leg'";

$result2=mysql_query($sql2);


if(mysql_num_rows($result2) == 0 and $sid != 0)
{
	if (mysql_num_rows($result2) == 0 && $sid != 0)
	{
		ini_set('display_errors',0);
		//echo "http://" .$_SERVER['HTTP_HOST'] ."/phpESP/public/css/template.css' type='text/css";exit;
		echo "<link rel='stylesheet' href='http://" .$_SERVER['HTTP_HOST'] ."/phpESP/public/css/template.css' type='text/css'>";
		
		include("./public/phpESP.first.php");
		
		include("./public/handler.php");
		
	}
}
else
{
	
	if(!$sid)
	{
			echo "No existe la encuesta para esta materia";
	}
	else
	{
		echo "YA COMPLETASTE ESTA ENCUESTA";
	}	
}

?>';
