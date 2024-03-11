<?php
require_once '../modelos/user.class.php';
require_once '../modelos/auth.class.php';
require_once '../respuestas/response.php';



$auth = new Authentication();  //crea un objeto con la tabla, la key privada.

//dependiendo del método request, tiene que ser un POST.
switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$user = json_decode(file_get_contents('php://input'), true);

		$token = $auth->signIn($user);

		$response = array(
			'result' => 'ok',
			'token' => $token
		);

		// Se devuelve el token correctamente.
		Response::result(201, $response);

		break;
}