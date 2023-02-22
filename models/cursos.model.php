<?php 

    require_once "conexion.php";

    class CursoModel {

        static public function index($tabla, $tabla2){

            $stmt=Conexion::conectar()->prepare("SELECT $tabla.id , $tabla.titulo , $tabla.descripcion , $tabla.instructor , $tabla.imagen , $tabla.precio , $tabla.id_creador, $tabla2.nombre, $tabla2.apellido FROM $tabla INNER JOIN $tabla2 ON $tabla.id_creador = $tabla2.id");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS);

            $stmt->close();
            
            $stmt=null;

        }
        static public function create($tabla, $datos){

            $stmt=Conexion::conectar()->prepare("INSERT INTO cursos(titulo, descripcion, instructor, imagen, precio) VALUES (:titulo ,:descripcion ,:instructor ,:imagen, :precio)");
            $stmt -> bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR); 
            $stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt -> bindParam(":instructor", $datos["instructor"], PDO::PARAM_STR);
            $stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio", $datos["precio"], PDO::PARAM_STR); 

            if($stmt -> execute()){
                return "ok";
            }else{
                print_r(Conexion::conectar()->errorInfo());
            }

        }

        static public function show($tabla, $tabla2, $id){

            $stmt=Conexion::conectar()->prepare("SELECT $tabla.id ,$tabla.titulo ,$tabla.descripcion,$tabla.instructor,$tabla.imagen,$tabla.precio,$tabla.id_creador ,$tabla2.nombre,$tabla2.apellido FROM $tabla INNER JOIN $tabla2 ON $tabla.id_creador = $tabla2.id WHERE $tabla.id=:id");

            $stmt -> bindParam(":id", $id , PDO::PARAM_INT); 

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS);

            $stmt->close();
            
            $stmt=null;


        }

        static public function update($tabla, $datos){

            $stmt=Conexion::conectar()->prepare("UPDATE cursos SET titulo=:titulo,descripcion=:descripcion,instructor=:instructor,imagen=:imagen,precio=:precio WHERE id=:id" );
           
            $stmt -> bindParam(":id", $datos["id"], PDO::PARAM_STR); 
            $stmt -> bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR); 
            $stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt -> bindParam(":instructor", $datos["instructor"], PDO::PARAM_STR);
            $stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio", $datos["precio"], PDO::PARAM_STR); 


            if($stmt -> execute()){

                return "ok";
    
            }else{
    
                print_r(Conexion::conectar()->errorInfo());
            }
    
            $stmt-> close();
    
            $stmt = null;

        }

        static public function delete ($tabla, $id){

            $stmt=Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id=:id");

            $stmt -> bindParam(":id", $id , PDO::PARAM_INT); 

            if($stmt->execute()){
                return "Ok";
            }else{
                print_r(Conexion::conectar()->errorInfo());
            }

            $stmt->close();
            
            $stmt=null;

        }

    }

   

?>