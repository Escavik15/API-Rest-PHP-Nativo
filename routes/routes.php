<?php
//declaramos las rutas

$arrayRoutes=explode("/", $_SERVER['REQUEST_URI']);



if(count(array_filter($arrayRoutes)) == 1 ){

    $json=array(
        "detalle"=>"no encontrado"
    );
    
    echo json_encode($json,true);

    return;

}else{

    
     //evaluamos el array de la URL para Cursos   
    if(count(array_filter($arrayRoutes)) == 2 ){
        //echo "<pre>"; print_r($arrayRoutes); echo "<pre>";    
        if(array_filter($arrayRoutes)[2] == "cursos"){
            //Metodo POST para agregar cursos
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST" ){
                //capturar datos
                $datos= array(      "titulo"=>$_POST["titulo"],
                                    "descripcion"=>$_POST["descripcion"],
                                    "instructor"=>$_POST["instructor"],
                                    "imagen"=>$_POST["imagen"],
                                    "precio"=>$_POST["precio"]);
                                  
               $cursos=new CursosController();
               $cursos->create($datos);

            }
            // metodo GET para obtener listado de cursos
            elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']== "GET"){
                $cursos=new CursosController();
                $cursos->index();
            }
       };
       
    };

    //evaluamos el array de la URL Registros
    if(count(array_filter($arrayRoutes)) == 2 ){

        if(array_filter($arrayRoutes)[2] == "registro"){

            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']== "POST"){
               
                $datos= array("nombre"   => $_POST["nombre"],
                              "apellido" => $_POST["apellido"],
                              "email"    => $_POST["email"]     );
            
                //echo "<pre>"; print_r($datos); echo "<pre>";    


                $clientes=new ClientesController();
                $clientes->create($datos);

            }
            
          

        };
       
    }else{
        
        if(array_filter($arrayRoutes)[2] == "cursos" && is_numeric(array_filter($arrayRoutes)[3]) ){
            //metodo para obtener un cursos por el ID
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']== "GET"){
               
                $curso=new CursosController();
                $curso->show(array_filter($arrayRoutes)[3]);

            }
            //metodo para Editar un Curso por el ID
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']== "PUT"){

                $datos= array();

                parse_str(file_get_contents('php://input'), $datos);
               
                //echo "<pre>"; print_r($datos); echo "<pre>";
              
                $editarCurso=new CursosController();
                $editarCurso->update(array_filter($arrayRoutes)[3],$datos);
                
            }
            // metodo para borrar un curso por el id
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']== "DELETE"){
               
                $borrarCurso=new CursosController();
                $borrarCurso->delete(array_filter($arrayRoutes)[3]);

            }
        }
    }

}


?>