<?php

class CursosController {

    public function index(){

        //validar credenciales del cliente
        //$user= $_SERVER['PHP_AUTH_USER'];
       // $pass= $_SERVER['PHP_AUTH_PW'];
        $clientes= ClienteModel::index('clientes');



        if(isset($_SERVER['PHP_AUTH_USER'])&& isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $value){
                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($value['id_cliente'].":".$value["llave_secreta"])){
                    $cursos=CursoModel::index("cursos","clientes");

                    $json=array(
                        "detalle"=> $cursos,
                    );
                    
                    echo json_encode($json,true);
                    printf(is_string($json));
                    return;
                }
            }


        }


       

    }
    public function create($datos){

        //validar credenciales del cliente
        $clientes= ClienteModel::index('clientes'); 

        if(isset($_SERVER['PHP_AUTH_USER'])&& isset($_SERVER['PHP_AUTH_PW'])){
            foreach($clientes as $key => $valueCliente){

                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente['id_cliente'].":".$valueCliente["llave_secreta"])){
                        //validar datos del curso
                        foreach($datos as $key => $valueDatos){
                            if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){

                                $json = array(
    
                                    "status"=>404,
                                    "detalle"=>"Error en el campo ".$key
    
                                );
    
                                echo json_encode($json, true);
    
                                return;
                            }
    
                        }
                        //validamos que el titulo o la descripcion no se repitan
                        $cursos = CursoModel::index("cursos");
                        foreach($cursos as $key => $value){
                            if($value->titulo == $datos["titulo"]){
                                $json = array(
                                    "status"=> 404,
                                    "detalle"=>"titulo repetido"
                                );
                            };
                            if($value->descripcion == $datos["descripcion"]){
                                $json = array(
                                    "status"=> 404,
                                    "detalle"=>"descripcion repetido"
                                );
                            };

                        }


                }

            }     
            //llevamos los datos al modelo
            $datos = array(
                "titulo"=>$datos["titulo"],
                "descripcion"=>$datos["descripcion"],
                "instructor"=>$datos["instructor"],
                "imagen"=>$datos["imagen"],
                "precio"=>$datos["precio"]
            );
           //echo "<pre>"; print_r($datos); echo "<pre>";   
            $create= CursoModel::create("cursos", $datos);

            //respuesta del modelo
            if($create == "ok"){
                $json = array(
                    "status"=>200,
                    "detalle"=>"registro exitoso"
                );
                echo json_encode($json,true);
                return;
            }
        }


        
        $json=array(
            "detalle"=>"estas en Create cursos"
        );
        
        echo json_encode($json,true);
    
        return;

    }

   public function show($id){
    //validar credenciales
    $clientes= ClienteModel::index('clientes'); 

            if(isset($_SERVER['PHP_AUTH_USER'])&& isset($_SERVER['PHP_AUTH_PW'])){
                foreach($clientes as $key => $valueCliente){

                    if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente['id_cliente'].":".$valueCliente["llave_secreta"])){

                            //mostrar los cursos
                            $curso = CursoModel::show("cursos", "clientes" ,$id);    
                            
                            if(empty($curso)){
                                $json=array(
                                    "status"=>404,
                                    "detalle"=>"curso no se encuentra"
                                );
                                
                                echo json_encode($json,true);
                            
                                return;
                            }else{

                                $json=array(
                                    "status"=>200,
                                    "detalle"=>$curso
                                );
                                
                                echo json_encode($json,true);
                            
                                return;  

                            }

                              

                    }
                }    
            }        
    }  
   
    public function update($id,$datos){
        //conexión a la tabla de clientes
        $clientes= ClienteModel::index('clientes');


        if(isset($_SERVER['PHP_AUTH_USER'])&& isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $valueCliente){
                if(base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_encode($valueCliente['id_cliente'].":".$valueCliente["llave_secreta"])){
                    $cursos=CursoModel::index("cursos");

                    foreach ($datos as $key => $valueDatos) {
                        if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){
                        $json=array(
                            "status"=>404,
                            "detalle"=>"Error en el campo ".$key
                          );
                    
                        echo json_encode($json,true);
                
                        return;
                    
                        }
                   }
                   $curso = CursoModel::show("cursos","clientes", $id);

                   foreach ($curso as $key => $valueCurso) {

                    if($valueCurso->id_creador == $valueCliente["id"]){
                           //llevamos los datos
                           
                           $datos = array( "id"=>$id,
											      "titulo"=>$datos["titulo"],
											      "descripcion"=>$datos["descripcion"],
											      "instructor"=>$datos["instructor"],
											      "imagen"=>$datos["imagen"],
											      "precio"=>$datos["precio"]);

                         
                            $update = CursoModel::update("cursos", $datos);                        


                        $json=array(
                            "status"=>200,
                            "detalle"=>$update
                          );
                    
                        echo json_encode($json,true);
                
                        return;
                }

            }}            
            }    

        }    



        
    }
          
   

   public function delete($id){

    $clientes = ClienteModel::index("clientes");

    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        foreach ($clientes as $key => $valueCliente) {
            if( "Basic ".base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
				"Basic ".base64_encode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"]) ){

					$curso = CursoModel::show("cursos","clientes" ,$id);

                    foreach ($curso as $key => $valueCurso) {
                        if($valueCurso->id_creador == $valueCliente["id"]){

                            $delete = CursoModel::delete("cursos", $id);
                            if($delete == "ok"){
                                $json = array(

                                    "status"=>200,
                                    "detalle"=>"se ha borrado el curso"
                                  
                                  );
  
                                echo json_encode($json, true);
  
                                return;
                            }

                        }
                    }
            }

        }
    }



   } 

};
