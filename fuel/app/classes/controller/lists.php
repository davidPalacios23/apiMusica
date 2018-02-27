<?php
use \Firebase\JWT\JWT;

class Controller_Lists extends Controller_Base
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
                if ( ! isset($_POST['title']))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'no han sido agregados todos los datos necesarios a la llamada'
                   ));

                    return $json;
                }

                $input = $_POST;
                //se controla que el usuario tenga el rol adecuado para poder hacer la petición
                if ($user->id_rol != 1) //esto es mejorable
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'usuario sin permisos'
                    ));

                    return $json;
                }
                //se controla que se introduzcan los datos necesarios y que no pueda haber campos vacios
                if (empty($input['title']))
                {
                    $json = $this->response(array(
                            'code' => 419,
                            'message' => 'debes introducir un título para la lista',
                            'data' => null,
                    ));
                    return $json;
                }


                //busco todas las listas del usuario en cuestión...
                $userLists = Model_Listsmodel::find('all', array(
                        'where' => array(
                            array('id_user', $user->id),
                        )
                    ));

                
                    // ... y compruebo que el nombre introducido no coincide con ninguno ya establecido
                    foreach ($userLists as $key => $list) {
                        if ($input['title'] == $list->title) 
                        {
                            
                            $json = $this->response(array(
                                'code' => 419,
                                'message' => 'esa lista ya existe',
                                'data' => 'null'
                            ));

                            return $json;    
                        }
                    }    
                
                
                $list = new Model_Listsmodel();
                $list->title = $input['title'];
                $list->id_user = $user->id;
                $list->save();

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'lista creada',
                    'name' => $input['title']
                ));

                return $json;
                

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

    public function post_addSong()
    {
        try 
        {

            $auth = self::authenticate();
        
            if($auth == true)
            {
                $decodedToken = self::decodeToken();
                $user = Model_Usersmodel::find($decodedToken->id);

                

                if ( ! isset($_POST['songName']) || ! isset($_POST['list']))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'parametros incorrectos'
                   ));

                    return $json;
                }

                $input = $_POST;

                if ($user->id_rol != 1) //esto es mejorable
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'usuario sin permisos'
                    ));

                    return $json;
                }

                if (empty($input['songName']) || empty($input['list']))
                {
                    $json = $this->response(array(
                            'code' => 419,
                            'message' => 'no puede haber campos vacíos',
                            'data' => null,
                    ));
                    return $json;
                }

                //compruebo que la canción existe en la bbdd
                $song = Model_Songsmodel::find('all', array(
                        'where' => array(
                            array('name', $input['songName']),
                        )
                ));
                
                if (empty($song)) {
                    $json = $this->response(array(
                                'code' => 400,
                                'message' => 'esa canción no existe'
                            ));

                            return $json;    
                }
                


                //busco todas las listas del usuario en cuestión...
                $userLists = Model_Listsmodel::find('all', array(
                        'where' => array(
                            array('id_user', $user->id),
                        )
                    ));


                    // ... y compruebo que el nombre introducido coincide con alguna lista de ese usuario
                    foreach ($userLists as $key => $list) {
                        
                        if ($input['list'] != $list->title) 
                        {
                            
                            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'esa lista no existe'
                            ));

                            return $json;    
                        }elseif ($input['list'] == $list->title) {
                            $listId = $list->id;
                        }

                    }

                $list = Model_Listsmodel::find($listId);
                $list->songs[$song->id] = Model_Songsmodel::find($song->id);
                $list->save();

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'cancion añadida a la lista',
                    'data' => null
                ));

                return $json;
                
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

    public function get_lists()
    {
    	$lists = Model_Listsmodel::find('all');

    	return $this->response(Arr::reindex($lists));
    }

    

    public function post_delete()
    {
        $list = Model_Listsmodel::find($_POST['id']);
        $listType = $list->type;
        $list->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'lista borrada',
            'name' => $userName
        ));

        return $json;
    }

  
 }   

    
