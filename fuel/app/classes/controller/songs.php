<?php
use \Firebase\JWT\JWT;

class Controller_Songs extends Controller_Base
{


    
    
    public function post_create()
    {
        try {
            //control de autenticación del token, si es el usuario logueado, entonces se puede acceder al endpoint
            $auth = self::authenticate();
        
            if($auth == true)
            {
                $decodedToken = self::decodeToken();
                $user = Model_Usersmodel::find($decodedToken->id);
                    
                //se controla que los datos estén definidos 
                if ( ! isset($_POST['name']) || ! isset($_POST['url']))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'no han sido agregados todos los datos necesarios a la llamada'
                   ));

                    return $json;
                }

                $input = $_POST;

                //se controla que los campos no estén vacíos

                if (empty($input['name']) || empty($input['url']))
                {
                    $json = $this->response(array(
                            'code' => 419,
                            'message' => 'no puede haber campos vacíos',
                            'data' => null,
                    ));
                    return $json;
                }
                 //se controla que solo un usuario con rol de administrador pueda crear canciones   
                if ($user->id_rol != 2) //esto es mejorable
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'usuario sin permisos'
                    ));

                    return $json;
                }


                else
                {
                    
                    $song = new Model_Songsmodel();
                    $song->name = $input['name'];
                    $song->url = $input['url'];
                    $song->save();

                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'canción creada',
                        'name' => $input['name']
                    ));

                    return $json;
                }

            }
        }     

        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
            ));

            return $json;
        }

        
    }

    public function get_songs()
    {
    	$songs = Model_Songsmodel::find('all');

    	//return $this->response(Arr::reindex($songs));
        $songsList = ["allSongs" => Arr::reindex($songs)];
        $json = $this->response(array(
                'code' => 200,
                'message' => 'lista de canciones',
                'data'=> $songsList
            ));

            return $json;
    }

    

    public function post_delete()
    {
        $song = Model_Songsmodel::find($_POST['id']);
        $songName = $song->name;
        $song->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'cancion borrada',
            'name' => $songName
        ));

        return $json;
    }

  
 }   

    
