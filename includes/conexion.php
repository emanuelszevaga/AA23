<?php
function conectar()
{
	global $con;
	$con = mysqli_connect("localhost:3307","root","admi151003","halloween");
		/* comprobar la conexión */
		if (mysqli_connect_errno()) {
            printf("Falló la conexión: %s\n", mysqli_connect_error());
            exit();
		} else {
				$con -> set_charset("utf8");
				$ret=true;
			}
		
	return $ret;
}
function desconectar()
{
	global $con;
	mysqli_close($con);
}
