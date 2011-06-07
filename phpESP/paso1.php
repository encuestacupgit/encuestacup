<?php
require_once('admin/include/where/wizardfunctions.inc');
$legajo=$_POST['userid'];
$result = getMateriasAlumno($legajo);



$host="localhost"; // Host name
$username="root"; // Mysql username
$password=""; // Mysql password
$db_name="phpesp"; // Database name
$conn = mysql_connect("$host", "$username", "$password")or die("No se puede conectar");
mysql_select_db("$db_name")or die("Imposible Conectar a la base de datos");

//echo intval(date('n'));exit;
$periodo = (intval(date('n')) >8)?'02':'01';

$sql="SELECT ps.title,cs.survey_id
	FROM phpesp_survey ps
	join cup_survey cs on cs.survey_id = ps.id  
	WHERE cs.periodo_curso	=	'".date('Y').$periodo."' AND cs.nro_materia = 0 AND cs.nro_curso_id= 0";
//echo $sql;//exit;
$resultGeneral = mysql_fetch_assoc(mysql_query($sql));
//var_dump($resultGeneral);
?>






<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Colegio Universitario de Periodismo - Encuesta Academica <?php echo date('Y')?></title>
		<style type="text/css">
			<!--
			body {
				background-color: #AAAAAA;
			}
			-->
		</style>
	</head>
	
	<body>
		<?php
			if(!$result)
			{?>
				<h2>no se a encontrado el alumno</h2>
			
		<?php exit; }?>
		
			<form action='paso2.php' method='POST' target='_blank'>
			
				<table 
					align='CENTER' 
					width='50%' 
					height='5%' 
					bgcolor='#AACCBB' 
					border='5' 
					bordercolor='#93DB70'>
					<?php 
					while ($row = mssql_fetch_assoc($result))
					{
						//var_dump($row);exit;
					?>
					<tr>
						<td>
							<input 
								type='radio' 
								name='radData' 
								value='<?php echo $row['periodo_curso'] . '|'. $row['nro_materia'] . '|' . $row['nro_curso_id']   ;?>'
							/>
			  				<?php echo $row['nom_materia']; ?>
						</td>
						<td>
			  				
			  				<?php echo $row['division_curso']; ?>
						</td>
						<td>
			  				<?php echo $row['turno_curso']; ?>
						</td>
					</tr>
	  			<?php
				}
				?>
				<?php
				if($resultGeneral!= false)
				{ 
				?>
				<tr>
						<td>
							<input 
								type="radio" 
								name="radData" 
								value="<?php echo date('Y').$periodo.'|0|0';?>"
							/>
			  				<?php echo $resultGeneral['title'];?>
						</td>
						<td>
			  				TODOS
						</td>
						<td>
			  				TODOS
						</td>
					</tr>
				<?php
				} 
				?>
			</table>
		<input 
			type='hidden' 
			value='<?php echo $legajo; ?>' 
			name='userid'
		/>
		<p 
			ALIGN='CENTER'>
			<input 
				type='submit' 
				value='Seleccionar' 
			/>
		</p>
	</form>
		
	</body>
</html>
