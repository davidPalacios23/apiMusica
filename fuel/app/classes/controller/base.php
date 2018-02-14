<?php
use \Firebase\JWT\JWT;
/**
* 
*/
class Controller_Base extends Controller_Rest
{
    private $key = "oiuo87i76f87t6uhi8gfg6r565465356436543uyftertewpoj+0okñoilbiuygi";


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