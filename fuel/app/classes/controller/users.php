<?php


class Controller_Users extends Controller_Base
{

    

    public function post_create()
    {
       
        try 
        {
             //se controla que los datos estén definidos   
            if ( ! isset($_POST['name']) || ! isset($_POST['password']) || ! isset($_POST['email']))
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'no han sido agregados todos los datos necesarios a la llamada'
                ));

                return $json;
            } 
                
            $input = $_POST;
            $users = Model_Usersmodel::find('all');

            //se comprueban campos vacios

            if (empty($input['name']) || empty($input['password']) || empty($input['email']) || empty($input['repeatPass'])) {
                $json = $this->response(array(
                        'code' => 419,
                        'message' => 'no puede haber campos vacíos',
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

            //se comprueba que la contraseña tenga al menos 6 caracteres
            if (strlen($input['password']) < 6) 
            {
                $json = $this->response(array(
                        'code' => 419,
                        'message' => 'La contraseña debe tener al menos 6 caracteres',
                        'data' => null,
                ));
                return $json;
            }
            // se comprueba que al repetir las contraseñas, sean iguales
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
        $email = $input['email'];
        
        //se comprueba que no haya campos vacios
        if (empty($email)) {
            $json = $this->response(array(
                'code' => 419,
                'message' => 'debes introducir el email',
                'data' => null,
            ));
            return $json;
        }

        $user = Model_Usersmodel::find('all', 
                                 ['where' => 
                                 ['email' => $email]]);

        if ($user != null){

            foreach ($user as $i => $objUser) {
                $id = $objUser->id;
                $password = $objUser->password;
                $birthday = $objUser->birthday;
                $description = $objUser->description;
                $location = $objUser->location;
                $x = $objUser->x;
                $y = $objUser->y;
                $name = $objUser->name;
                $rolId = $objUser->id_rol;

            }   
            $userToken = ["name" => $name, "password" => $password, "email" => $email, "id" => $id, "birthday" => $birthday, "description" => $description, "location" => $location, "x" => $x, "y" => $y, "id_rol" => $rolId];
            $token = self::encode($userToken);
            $json = $this->response(array(
                'code' => 200,
                'message' => 'Email valido',
                'data' => $token
            ));

            return $json;
        }else
        {
            //se comprueba que el email exista y coincida con el escrito por el usuario
            $json = $this->response(array(
                'code' => 419,
                'message' => 'El email introducido no coincide o no existe',
                'data' => null,
            ));
            return $json;
        }


    }
    public function post_recoverPass(){
        

        $input = $_POST;
        $password = $input['password'];
        $repeatPass = $input['repeatPass'];
        
        $auth = self::authenticate();
        
        if($auth == true)
        {
            //se controla que los datos estén definidos
            if ( ! isset($input['password']) || ! isset($input['repeatPass']) ) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'No han sido agregados todos los datos necesarios a la llamada',
                    'data' => null,
                ));
                return $json;
            }

            //el siguiente condicional sirve para comprobar que los campos de usuario o email no estén vacíos
            if (empty($input['password']) || empty($input['repeatPass'])) {
                $json = $this->response(array(
                    'code' => 419,
                    'message' => 'no puede haber campos vacios',
                    'data' => null,
                ));
                return $json;
            }
            //se comprueba que las contraseñas coincidan para evitar errores de escritura
            if ($input['password'] != $input['repeatPass']) {
                $json = $this->response(array(
                            'code' => 419,
                            'message' => 'Las contraseñas deben coincidir',
                            'data' => null,
                    ));
                    return $json;
            }

            $decodedToken = self::decodeToken();
            $user = Model_Usersmodel::find($decodedToken->id);
            $user->password = $input['password'];
            $user->save();

                    
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'contraseña modificada',
                        'data' => null,
                    ));

                     return $json;
        } 
        else
        {
            //Se devuelve una respuesta 401 en caso de no haber podido ser autenticado
            $json = $this->response(array(
                'code' => 401,
                'message' => 'No ha podido ser autenticado',
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

        //se comprueba que las credenciales introducidas son correctas y ese usuario existe en la bbdd
        if (empty($users)) 
        {
            $json = $this->response(array(
                'code' => 401,
                'message' => 'fallo autenticacion',
                'data' => '' 
            ));
            return $json;
        }

        foreach ($users as $i => $objUser) {
                $id = $objUser->id;
                $password = $objUser->password;
                $birthday = $objUser->birthday;
                $description = $objUser->description;
                $location = $objUser->location;
                $email = $objUser->email;
                $x = $objUser->x;
                $y = $objUser->y;
                $name = $objUser->name;
                $rolId = $objUser->id_rol;

            }   
            $userToken = ["name" => $name, "password" => $password, "email" => $email, "id" => $id, "birthday" => $birthday, "description" => $description, "location" => $location, "x" => $x, "y" => $y, "id_rol" => $rolId];
            $encodedToken = self::encode($userToken);
            $json = $this->response(array(
            'code' => 200,
            'message' => 'login correcto',
            'data' => $encodedToken 
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

    
