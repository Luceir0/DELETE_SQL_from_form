<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Consulta parametrizada SQL PDO</title>
    </head>
    <body>
        <?php
        require_once 'conexiones.inc.php';
        
        echo '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
        echo 'Nombre <input type="text" name="nombre"> </br> </br>';
        echo 'Nif <input type="text" name="nif"> </br> </br>';
        echo 'Apellido 1 <input type="text" name="ap1"> </br> </br>';
        echo 'Apellido 2 <input type="text" name="ap2"> </br> </br>';
        echo '<input value="Consultar" type="submit" name="aceptar"> </br> </br>';
        echo '</form>';
        
        //Valores iniciales de strings de consulta:
        
        $initialQuery = 'SELECT * FROM persona';
        $restrictions = '';
        
        //Posibles parámetros que se pueden tener en cuenta en la consulta:
        
        $possibleParams = ['nif', 'nombre', 'ap1', 'ap2'];
        
        if (count ($_POST) > 1){
            if (!isset ($_POST["borrar"])){
                try {
                    //Recorrido de los posibles parámetros teniendo en cuenta los valores de $_POST, incluyendo las restricciones a string $restrictions:
                        foreach ($possibleParams as $oneParam) {
                            if(!empty ($_POST[$oneParam])) {
                                if (empty($restrictions)){
                                    $restrictions = $restrictions . 'WHERE ' . $oneParam . '=:' . $oneParam;
                                } else {
                                    $restrictions = $restrictions . ' AND ' . $oneParam . '=:' . $oneParam;
                                }
                            }
                        }

                        //Conexión:

                       $con = conecta();

                       //Formateo final de la query:

                       $stringQuery = $initialQuery . ' ' . $restrictions;

                       //Preparación de la consulta:

                        $consulta = $con -> prepare($stringQuery);

                        //Bindeo de parámetros, y asociación de valores:

                        if(!empty ($_POST["nif"])) {
                          $consulta -> bindParam(':nif', $parametro_nif, PDO::PARAM_STR);
                          $parametro_nif = $_POST["nif"];
                        } 
                        if (!empty ($_POST["nombre"])){
                          $consulta -> bindParam(':nombre', $parametro_nombre, PDO::PARAM_STR);
                          $parametro_nombre = $_POST["nombre"];
                        } 
                        if (!empty ($_POST["ap1"])){
                          $consulta -> bindParam(':ap1', $parametro_ap1, PDO::PARAM_STR);
                          $parametro_ap1 = $_POST["ap1"];
                        } 
                        if (!empty ($_POST["ap2"])){
                          $consulta -> bindParam(':ap2', $parametro_ap2, PDO::PARAM_STR);
                          $parametro_ap2 = $_POST["ap2"];
                        }

                        //Ejecución de la consulta:

                        $consulta -> execute();

                        //Muestra de datos en pantalla, y opción de borrado según checkbox:

                   echo '<form action="index.php" method="post">';
                        echo '<div>';
                            echo '<p>Usuarios:</p>';
                                while ($registro = $consulta -> fetch()) {
            //                        echo $registro["nif"] . '/' . $registro["nombre"] . '/' . $registro["ap1"] . '/' . $registro["ap2"] . '<br/>';
                                    echo "<input value='$registro[id_persona]' type=checkbox name='$registro[id_persona]' /> $registro[nif]  $registro[nombre]  $registro[ap1] $registro[ap2]  </br>";
                                }
                            echo '<br/><button type="submit" name="borrar">Borrar usuario</button>';
                         echo '</div>';
                    echo '</form>';
                                            

                    //Catch de errores:

                } catch (PDOException $e) {
                    echo $e -> getMessage();
                }finally {

                    //Cierre de consulta y conexión:

                    $consulta = null;
                    $con = null;
                }
            } else {
                
                //Borrado de usuario según el checkbox marcado:
                
              if (count ($_POST) > 0) {
                  try {
                      $con = conecta();
                      foreach($_POST as $clave => $valor) {
                          //En lugar de su clave, podríamos usar también el valor del botón, que será el mensajito que guarda dentro (en este caso, 'Borrar usuario'):
                        if($clave != "borrar"){   
                            
                                $sql2 = "DELETE FROM persona WHERE id_persona=:id";
                                $consulta = $con -> prepare($sql2);
                                
                                //Estoy bindeando :id a la variable $parametro_id. Hasta el momento, :id era un placeholder.
                                $consulta -> bindParam(':id', $parametro_id, PDO::PARAM_INT);
                                
                                foreach ($_POST as $id) {
                                    //En cada vuelta le estoy cambiando el valor a $parametro_id, para que la consulta sea diferente de cada vez.
                                    $parametro_id = $id;
                                    $consulta -> execute();
                                 }
                                 
                             echo '<p>El usuario ha sido correctamente eliminado de nuestra base de datos.';   
                         }  
                      }
                  } catch (PDOException $e) {
                      echo $e ->getMessage();
                  } finally {
                    $con = null;  
                  }
               }      
            }
        }

        ?>
    </body>
</html>
