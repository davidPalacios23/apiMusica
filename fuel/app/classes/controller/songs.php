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
                if ($user->id_rol != 9)
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'usuario sin permisos'
                    ));

                    return $json;
                }else
                {
                    $input = $_POST;
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

    public function get_users()
    {
    	$users = Model_Usersmodel::find('all');

    	return $this->response(Arr::reindex($users));
    }

    public function get_login()
    {
        
        $userName = $_GET['name'];
        $userPass = $_GET['password'];

        $users = Model_Usersmodel::find('all', array(
            'where' => array(
                array('nombre', $userName),
                array('password', $userPass)
            )
        ));

        if (empty($users)) 
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'fallo autenticacion',
                'data' => '' 
            ));
            return $json;
        }

        foreach ($users as $key => $user) 
        {
            $user = [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'password' => $user->password
            ];
        }

        $token = JWT::encode($user, $this->key);

             
        $json = $this->response(array(
            'code' => 200,
            'message' => 'login correcto',
            'data' => $token 
        ));
        return $json;
       
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

    
