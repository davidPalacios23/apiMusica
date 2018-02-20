<?php
use \Firebase\JWT\JWT;

class Controller_Songs extends Controller_Base
{


    
    
    public function post_create()
    {
        try {

            $auth = self::authenticate();
        
            if($auth == true)
            {
                $decodedToken = self::decodeToken();
                $user = Model_Usersmodel::find($decodedToken->id);
                    

                if ( ! isset($_POST['name']) || ! isset($_POST['url']))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'parametros incorrectos'
                   ));

                    return $json;
                }

                $input = $_POST;

                if (empty($input['name']) || empty($input['url'])
                {
                    $json = $this->response(array(
                            'code' => 419,
                            'message' => 'no puede haber campos vacíos',
                            'data' => null,
                    ));
                    return $json;
                }
                    
                if ($user->id_rol != 11) //en este caso es 11 porque al ser autoincremental, el id va subiendo aunque borres los roles de la bbdd, pero cuando funcione bien será el numero 2
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

    	return $this->response(Arr::reindex($songs));
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

    
