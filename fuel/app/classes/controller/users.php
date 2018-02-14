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

            if (empty($input['name']) || empty($input['password']) || empty($input['email']) || empty($input['repeatPass'])) {
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

            if ($input['password'] != $input['repeatPass']) {
                $json = $this->response(array(
                        'code' => 419,
                        'message' => 'Las contraseñas deben coincidir',
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
                
                // busco todos los roles cuyo tipo sea 'usuario' y los guardo en rolUser
                $rolUser = Model_Rolesmodel::find('all', array(
                    'where' => array(
                        array('type', 'usuario'),
                    )
                ));

                // recorro el array de rolUser y guardo su id en idRol
                foreach ($rolUser as $key => $rol) 
                {
                    $idRol = $rol->id;
                }

                //se guardan los datos en la base de datos y se devuelve la respuesta
                $user = new Model_Usersmodel(); 
                $user->name = $input['name'];
                $user->password = $input['password'];
                $user->email = $input['email'];
                $user->id_rol = $idRol;
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
                'error' => $e->getMessage()
            ));

            return $json;
        }

        
    }


    public function get_users()
    {
    	$users = Model_Usersmodel::find('all');

    	return $this->response(Arr::reindex($users));
    }

    public function get_checkEmail()
    {
        $input = $_GET;

    }
    public function get_recover_pass(){
        

        if ( ! isset($_GET['name']) || ! isset($_GET['email']) ) 
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'No han sido agregados todos los parametros necesarios a la llamada',
                'data' => null,
            ));
            return $json;
        }

        //el siguiente condicional sirve para comprobar que los campos de usuario o email no estén vacíos
        if (empty($_GET['name']) || empty($_GET['email'])) {
            $json = $this->response(array(
                'code' => 419,
                'message' => 'no puede haber parametros vacios',
                'data' => null,
            ));
            return $json;
        }
        //Se recogen los datos necesarios para validar al usuario
        $input = $_GET;   
        $name = $input['name'];
        $email = $input['email'];
        //Se busca en la base de datos el usuario 
        $user = Model_Users::find('all', 
                                 ['where' => 
                                 ['name' => $name, 
                                  'email' => $email]]);

        if($user != null)
        {
            //Se transforman los campos de id y email para enviarlos facilmente
            foreach ($user as $i => $objUser) {
                $id = $objUser->id;
                $pass = $objUser->pass;
            }   
            $userToken = ["name" => $name, "pass" => $pass, "email" => $email, "id" => $id];//Se convierten los datos a un array relaccional para gestionar mejor el token
            //Se codifica el token 
            $encodedToken = self::encode($userToken);
            //La respuesta devuelve un código 200 y el token que necesitará para realizar las acciones dentro de la app
            $json = $this->response(array(
                'code' => 200,
                'message' => 'Usuario validado',
                'data' => ['token' =>$encodedToken],
            ));
        }
        else
        {
            //En caso de ser un fallo al introducir los datos el usuario se devuelve un error 419
            $json = $this->response(array(
                'code' => 419,
                'message' => 'El usuario y el email introducidos no son coincidentes o no existen',
                'data' => null,
            ));
        }
    }

    public function get_login()
    {
        
        $userName = $_GET['name'];
        $userPass = $_GET['password'];

        $users = Model_Usersmodel::find('all', array(
            'where' => array(
                array('name', $userName),
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
                'name' => $user->name,
                'password' => $user->password
            ];
        }

        $token = self::encode($user);

             
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
        $userName = $user->name;
        $user->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'usuario borrado',
            'name' => $userName
        ));

        return $json;
    }

  
 }   

    
