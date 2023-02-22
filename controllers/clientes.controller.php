<?php

class ClientesController {

    public function create($datos){
        //echo "<pre>"; print_r($datos); echo "<pre>";    

        if(isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["nombre"])){
            $json=array(
                "status" => 404,
                "detalle"=> "error en el campo del nombre"
            );
            
            echo json_encode($json,true);
        
            return;
        }
        if(isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["apellido"])){
            $json=array(
                "status" => 404,
                "detalle"=> "error en el campo del apellido"
            );
            
            echo json_encode($json,true);
        
            return;
        }
        //evaluamos que el email intoducido sea de formato correcto
        if(isset($datos["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos["email"])){
            $json=array(
                "status" => 404,
                "detalle"=> "error en el campo del email"
            );
            
            echo json_encode($json,true);
        
            return;
        }
        //evaluamos que el email no sea repetido

        $clientes= ClienteModel::index("clientes");

            foreach($clientes as $key => $value){

                if($value["email"] == $datos["email"]){
                    $json=array(
                        "status" => 404,
                        "detalle"=> "email repetido"
                    );
                    
                    echo json_encode($json,true);
                
                    return;
                }
            }

    //Generamos credenciales del Cliente
    
    $id_cliente = str_replace("$", "c", crypt($datos["nombre"].$datos["apellido"].$datos["email"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
    //echo "<pre>"; print_r($id_cliente); echo "<pre>";
    $llave_secreta =  str_replace("$", "a", crypt($datos["email"].$datos["apellido"].$datos["nombre"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));    
    //echo "<pre>"; print_r($secret_key); echo "<pre>";
    $datos=array("nombre"       =>$datos["nombre"],
                 "apellido"     =>$datos["apellido"],
                 "email"        =>$datos["email"],
                 "id_cliente"   =>$id_cliente,
                 "llave_secreta"=>$llave_secreta
                );
    $create= ClienteModel::create("clientes", $datos);
    if($create == "ok"){
            $json=array(
                "status" => 200,
                "detalle"=> "correcto",
                "credenciales"=>$id_cliente,
                "llave secreta"=>$llave_secreta
            );
         
        
        echo json_encode($json,true);
    
        return;
    }

}


}



?>