<?php
use \Firebase\JWT\JWT;

class Controller_Users extends Controller_Rest
{


    private $key = 'posdfnopiwejrmovjdoisjv0`98hgfq2482q84hf078h4f98j23409583240ujelq2';
    
    public function post_create()
    {
        try {
            if ( ! isset($_POST['name']) || ! isset($_POST['password'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametros incorrectos'
                ));

                return $json;
            }

            $input = $_POST;
            $user = new Model_Usersmodel();
            $user->nombre = $input['name'];
            $user->password = $input['password'];
            $user->save();

            $json = $this->response(array(
                'code' => 200,
                'message' => 'usuario creado',
                'name' => $input['name']
            ));

            return $json;

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

    
