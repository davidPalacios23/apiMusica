<?php
use \Firebase\JWT\JWT;

class Controller_Lists extends Controller_Base
{


    public function post_create()
    {
        try {

            $auth = self::authenticate();
        
            if($auth == true)
            {
                $decodedToken = self::decodeToken();
                $user = Model_Usersmodel::find($decodedToken->id);
                    

                if ( ! isset($_POST['title']))
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
                                'code' => 400,
                                'message' => 'esa lista ya existe'
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

    public function get_lists()
    {
    	$users = Model_Usersmodel::find('all');

    	return $this->response(Arr::reindex($users));
    }

    

    public function post_delete()
    {
        $user = Model_Usersmodel::find($_POST['id']);
        $userName = $user->nombre;
        $user->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'usuario borrado',
            'name' => $userName
        ));

        return $json;
    }

  
 }   

    
