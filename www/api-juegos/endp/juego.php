<?php
require_once '../respuestas/response.php';
require_once '../modelos/juego.class.php';
require_once '../modelos/auth.class.php';




$auth = new Authentication();
//Compara que el token sea el correcto 
$auth->verify();



//hasta aquí, el token está perfectamente verificada. Creamos modelo para que pueda gestionar las peticiones
$juego = new juego();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$params = $_GET;  //aquí están todos los parámetros por url

       // $auth->insertarLog(); exit;
        //si pasamos un id del usuario, comprobamos que sea el mismo que el del token
        if (isset($_GET['id_usuario']) && !empty($_GET['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($_GET['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
			    exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }

        $juegos = $juego->get($params);
        //$auth->insertarLog('lleva a solicitud de juegos');
        $url_raiz_img = "http://".$_SERVER['HTTP_HOST']."/api-juegos/public/img";
		for($i=0; $i< count($juegos); $i++){
			if (!empty($juegos[$i]['imagen']))
				$juegos[$i]['imagen'] = $url_raiz_img ."/". $juegos[$i]['imagen'];
		}

        $response = array(
            'result'=> 'ok',
            'juegos'=> $juegos
        );
       // $auth->insertarLog('devuelve juegos'); 
        Response::result(200, $response);
        break;
    
    case 'POST':
        $params = json_decode(file_get_contents('php://input'), true);
     
      
        if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            //echo "Pasamos id_usuario es ".$_GET['id_usuario']." y el id del token es ".$auth->getIdUser();
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id pasado por body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
			    exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }




        $insert_id_juego = $juego->insert($params);
        //Debo hacer una consulta, para devolver tambien el nombre de la imagen.
        $id_param['id'] = $insert_id_juego;
        $juego = $juego->get($id_param);
        if($juego[0]['imagen'] !='')
            $name_file =  "http://".$_SERVER['HTTP_HOST']."/api-juegos/public/img/".$juego[0]['imagen'];
        else
            $name_file = '';

        $response = array(
			'result' => 'ok insercion',
			'insert_id' => $insert_id_juego,
            'file_img'=> $name_file
		);

		Response::result(201, $response);
        break;


    case 'PUT':
		$params = json_decode(file_get_contents('php://input'), true);

        if (!isset($params) || !isset($_GET['id']) || empty($_GET['id'])  ){
            $response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud de actualización del juego. No has pasado el id del juego'
			);

			Response::result(400, $response);
			exit;
        }

         if (isset($params['id_usuario']) && !empty($params['id_usuario'])){
            
            if ($params['id_usuario'] != $auth->getIdUser()){
                $response = array(
                    'result' => 'error',
                    'details' => 'El id del body no corresponde con el del usuario autenticado. '
                ); 
                Response::result(400, $response);
			    exit;
            }
        }else{
            //hay que añadir a $params el id del usuario.
            $params['id_usuario'] = $auth->getIdUser();
        }


        $juego->update($_GET['id'], $params);  //actualizo ese juego.
        $id_param['id'] = $_GET['id'];
        $juego = $juego->get($id_param);
       

        if($juego[0]['imagen'] !='')
            $name_file =  "http://".$_SERVER['HTTP_HOST']."/api-juegos/public/img/".$juego[0]['imagen'];
        else
            $name_file = '';
            
        $response = array(
			'result' => 'ok actualizacion',
            'file_img'=> $name_file
		);



		Response::result(200, $response);
        break;


    case 'DELETE':
        if(!isset($_GET['id']) || empty($_GET['id'])){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}

		$juego->delete($_GET['id']);

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);
		break;

	default:
		$response = array(
			'result' => 'error'
		);

		Response::result(404, $response);

		break;


    }

?>