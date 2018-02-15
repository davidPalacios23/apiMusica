<?php
use \Firebase\JWT\JWT;
/**
* 
*/
class Controller_Base extends Controller_Rest
{
    private $key = "oiuo87i76f87t6uhi8gfg6r565465356436543uyftertewpoj+0okñoilbiuygi";

    //debo ver como llamar a esta funcion al iniciar la aplicación
    public function post_createRolTypes(){

        try{

            $rolUser = new Model_Rolesmodel();
                    $rolUser->type = "usuario";
                    $rolUser->save();

            $rolAdmin = new Model_Rolesmodel();
                    $rolAdmin->type = "administrador";
                    $rolAdmin->save();        

            $rolAdminId = Model_Rolesmodel::find('all', array(
                        'where' => array(
                            array('type', 'administrador'),
                        )
                    ));

                    // recorro el array de rolUser y guardo su id en idRol
                    foreach ($rolAdminId as $key => $rol) 
                    {
                        $idRol = $rol->id;
                    }

            $user = new Model_Usersmodel(); 
                    $user->name = $this->encode("David");
                    $user->password = $this->encode("123456");
                    $user->email = $this->encode("davidpalacios23@hotmail.com");
                    $user->id_rol = $idRol;
                    $user->save();

                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'administrador y roles creados',
                        'name' => null
                    ));

                     return $json;

        }catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => "error interno del servidor",
                'error' => $e->getMessage()
            ));

            return $json;
        }

        
    }
    
    protected function encode($data)
    {
        return JWT::encode($data, $this->key);
    }

    protected function decode($data)
    {
        return JWT::decode($data, $this->key, array('HS256'));
    }
    
    protected function decodeToken(){
        $header = apache_request_headers();
        $token = $header['Authorization'];
        if(!empty($token))
        {
            return $this->decode($token);
        }      
    }
    
    protected function authenticate(){
        try {
               
            $header = apache_request_headers();
            $token = $header['Authorization'];

            if(!empty($token))
            {
                $decodedToken = self::decode($token);
                
                $query = Model_Users::find('all', 
                    ['where' => ['name' => $decodedToken->name, 
                                 'password' => $decodedToken->password, 
                                ]]);
                
                if($query != null)
                {
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        } 
        catch (Exception $UnexpectedValueException)
        {
            return false;
        }
    }
    public function get_default_auth()
    {  
        $auth = self::authenticate();
        if($auth == true)
        {
            $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Usuario autenticado',
                    'data' => null
            ));
            return $json;
        }else{
            $json = $this->response(array(
                    'code' => 401,
                    'message' => 'Usuario no autenticado',
                    'data' => null
            ));
            return $json;
        }
        
    }
}