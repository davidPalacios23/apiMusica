<?php
use \Firebase\JWT\JWT;

class Controller_Users extends Controller_Rest
{


    private $key = 'posdfnopiwejrmovjdoisjv0`98hgfq2482q84hf078h4f98j23409583240ujelq2';
    
    public function post_create()
    {
       
        try 
        {
                
            if ( ! isset($_POST['name']) || ! isset($_POST['password']) || ! isset($_POST['email']))
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametros incorrectos'
                ));

                return $json;
            } 
                
            

            $input = $_POST;
            $users = Model_Usersmodel::find('all');

            //se comprueban campos vacios

            if (empty($input['name']) || empty($input['password']) || empty($input['email'])) {
                $json = $this->response(array(
                        'code' => 419,
                        'message' => 'no puede haber parámetros vacíos',
                        'data' => null,
                   ));
                return $json;

            }

            // se comprueba que el usuario introducido no esté en la base de datos    
            foreach ($users as $key => $user) {
                if ($input['name'] == $user->name) {
                    
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'nombre ya en uso'
                    ));

                    return $json;    
                }
            }
            if (strlen($input['password']) < 6) 
            {
                $json = $this->response(array(
                        'code' => 419,
                        'message' => 'La contraseña debe tener al menos 6 caracteres',
                        'data' => null,
                ));
                return $json;
            }
            //Se valida si el email esta en un formato válido
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) 
            {
                $json = $this->response(array(
                        'code' => 419,
                        'message' => 'El formato de email introducido no es válido',
                        'data' => null,
                   ));
                return $json;
            }
            // se comprueba que el email introducido no esté en la base de datos  
            foreach ($users as $key => $user) {
                if ($input['email'] == $user->email) {
                    
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'email ya en uso'
                    ));

                    return $json;    
                }
            }
            /* mirando lo de asignar un rol al primer usuario
            foreach ($users as $key => $user) {
                if ($user->id == 0) {
                    $user = new Model_Usersmodel(); 
                    $user->name = $input['name'];
                    $user->password = $input['password'];
                    $user->email = $input['email'];
                    $user->rol
                    $user->save();
                }*/
                //se guardan los datos en la base de datos y se devuelve la respuesta
                $user = new Model_Usersmodel(); 
                $user->name = $input['name'];
                $user->password = $input['password'];
                $user->email = $input['email'];
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
                'message' => "error interno del servidor",
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

    
