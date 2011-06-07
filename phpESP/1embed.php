<?php
/*
$host="localhost"; // Host name
$username="root"; // Mysql username
$password="root"; // Mysql password
$db_name="phpesp"; // Database name
//$tbl_name="alumnos"; // Table name

/*agregar consulta sql, para que busque en la tabla response el id de la encuesta y el legajo del alumno para verificar que no la respondio*/
/*
$conn=mysql_connect($host, $username, $password)or die("No se puede conectar");
mysql_select_db($db_name)or die("Imposible Conectar a la base de datos");
*/

	$data 		= 	explode('|', $_POST['radData']);
	$materia 	= 	$data[0];//$_POST ['materias'];
	$division 	=	$data[1];//$_POST['quecurso'];
	$turno 		=	$data[2];//$_POST['quecurso'];
	$leg		=	$_POST['userid'];
//	$arreglo=$_POST['row'];
//--------------------------------------------CONSULTA SQL-----------------------

//echo "$leg</br>";
//echo "$curso</br>";
//echo "$materia</br>";
/*echo "$arreglo[0]</br>";
echo "$arreglo[1]</br>";
echo "$arreglo[2]</br>";*/



$sql_old="SELECT i.division_curso 
		FROM inscripciones_cursar i, materias m 
		WHERE 
			i.nro_leg_alumno = '$leg' 
			AND i.cod_materia = m.cod_materia 
			AND i.cod_carrera = m.cod_carrera 
			AND i.cod_titulo = m.cod_titulo 
			AND i.cod_plan_estudio = m.cod_plan_estudio 
			AND m.nom_materia = '$materia'";

if($materia != 'ENCUESTA DE SATISFACCION')
{
	
	$sql = "
		SELECT c.division_curso, c.turno_curso
	FROM inscripciones_cursar ic
		
		JOIN programas_academicos pa ON ic.nro_prog_acad = pa.nro_prog_acad
		JOIN materias ma ON ic.nro_materia = ma.nro_materia
		JOIN legajos_alumnos la ON ic.nro_leg_alumno = la.nro_leg_alumno
		JOIN identificacion_personas ip ON la.nro_persona = ip.nro_persona
		JOIN cursos c ON ic.nro_curso_id = c.nro_curso_id and ic.nro_materia = c.nro_materia and ic.periodo_curso = c.periodo_curso
		JOIN planes_estudios pe ON ic.nro_prog_acad = pe.nro_prog_acad AND ic.nro_materia = pe.nro_materia
		WHERE (ic.periodo_curso = '201002' or (ic.periodo_curso = '201001' AND pe.sem_materia is null))  
			AND ic.nro_leg_alumno = " . $leg ." 
			AND ma.nom_materia = CONVERT('" . $materia .  "' USING utf8)"; 
	$result = mysql_query($sql);
	while ($fila = mysql_fetch_row($result))
	{
		
		//echo "$fila[0]";
		$curso=$fila[0];
		$turno=$fila[1];
	}
}
else
{
	$curso = 'U';
	$turno = 'UNICO';
}
//echo "$curso</br>";
$sql="SELECT id 
		FROM in_sur_new 
		WHERE materia	=	'$materia' 
		AND division	=	'$curso'
		AND turno		=	'$turno'";

$result = mysql_query($sql);

if($result)
{
	while ($fila2 = mysql_fetch_array($result)){
	//echo "$fila[0]";
		
		$sid=$fila2[0];
	}
}

//echo "$sid";
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
