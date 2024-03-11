<?php
require_once '../jwt/JWT.php';
require_once '../modelos-datos/authModel.php';
require_once '../respuestas/response.php';
use Firebase\JWT\JWT;

class Authentication extends AuthModel
{
	private $table = 'usuarios';
	private $key = 'clave_secreta_muy_discreta';
	private $idUser ='';




	public function signIn($user)
	{
		if(!isset($user['email']) || !isset($user['password']) || empty($user['email']) || empty($user['password'])){
			$response = array(
				'result' => 'error',
				'details' => 'Los campos password y email son obligatorios'
			);
			
			Response::result(400, $response);
			exit;
		}

		$result = parent::login($user['email'], hash('sha256' , $user['password']));

		if(sizeof($result) == 0){
			$response = array(
				'result' => 'error',
				'details' => 'El email y/o la contraseña son incorrectas'
			);

			Response::result(403, $response);
			exit;
		}

		

		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $result[0]['id'],
				'email' => $result[0]['email']
			)
		);


		$jwt = JWT::encode($dataToken, $this->key);

		parent::update($result[0]['id'], $jwt);

		return $jwt;
	}





	public function getIdUser(){
		return $this->idUser;
	}





/**
 * Este método es llamado para verificar si el usuario está autenticado.
 * Comprueba que lleva la cabecera HTTP_AUTHORIZATION. Nosotros incluímos en la cabecera
 * el parámetro Autentication, pero el protocolo HTTP lo transforma cambiando a mayúsculas
 * y en vez de un guion, utiliza un guión bajo. También le precede con HTTP. Por tanto le 
 * queda como HTTP_API_KEY en vez de api_key.
 * 
 * Preguntamos por HTTP_AUTHORIZATION. Si no existe, nos devuelve un error donde no tenemos autorización.
 * Si existe, guarda en la variable jwt ese token que vino por la cabecera petición GET.
 * Llamamos a la librería JWT pasándole el token, la key que es privada y el algoritmo. Si la clave secreta
 * es otra, no será la misma por tanto saldrá un error. Por eso es tan importante la clave privada.
 * 
 * Si el token es diferente, lanzamos una excepción. La excepción se produce por el método decode. Si alguien
 * intenta descifrar el token, necesita obligatoriamente la clave privada.
 * 
 *  En caso de que sea correcta y no se haya producido la excepción, debemos de buscar 
 * el usuario en nuestra base de datos. Para ello, en la desencriptación sacamos el id del usuario. Llamamos
 * al getById a partir del usuario y trae su token. Hay que comparar el token del usuario en la BBDD
 * con el token pasado que es jwt. En cuyo caso, no tiene permisos para la solicitud.
 * Si todo ha ido bien, la función finaliza y seguirá con el flujo de ejecución normal de la clase que
 * invocó a este método que es user.php
 * 
 */

	public function verify()
    {
		

        if(!isset($_SERVER['HTTP_API_KEY'])){ 
			
			echo "No existe HTTP_API_KEY";
            $response = array(
                'result' => 'error',
                'details' => 'Usted no tiene los permisos para esta solicitud'
            );
        
            Response::result(403, $response);
            exit;
        }
		
        $jwt = $_SERVER['HTTP_API_KEY'];

        try {
            $data = JWT::decode($jwt, $this->key, array('HS256'));
			//echo "paso";
			$user = parent::getById($data->data->id);
			$this->idUser = $data->data->id;
			//echo $user; exit;

			if($user[0]['token'] != $jwt){
				throw new Exception();
			}
			
			//$this->insertarLog('autenticado correctamente'); exit; 
            return $data;
        } catch (\Throwable $th) {

           //$this->insertarLog( $_SERVER['HTTP_API_KEY']); exit; 
		   
            $response = array(
                'result' => 'error',
                'details' => 'No tiene los permisos para esta solicitud'
            );
			
            Response::result(403, $response);
            exit;
        }
		 
		

    }


	


	public function modifyToken($id, $email){
		
		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $id,
				'email' => $email
			)
		);

		$jwt = JWT::encode($dataToken, $this->key);

		parent::update($id, $jwt);

		return $jwt;

	}

	public  function igualesIdUser($id){
		return $id==$this->idUser;
	}

	public function insertarLog($milog){
		parent::insertarLog($milog);
	}
}
