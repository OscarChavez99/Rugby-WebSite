<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="obtener_rank.js"></script>
    <!--Tensorflow-->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script>
        var modelo = null;
        CargarModelo();
        var contraInvalida = "Ingrese una contraseña con al menos 7 caracteres";
        function ValidarContrasena(){
            if(registro.password.value.length >= 7) {
                return true;
            } else 
                return false;
        }
        function ValidarCampos() {
            var mensaje = "Faltan campos por llenar";
            var contra = ValidarContrasena();
            
            if (!contra) {
                alert(contraInvalida);
                return false; // Evitar el envío del formulario
            } else if (!registro.codigo.value || !registro.nombre.value || !registro.peso.value || !registro.altura.value
                || !registro.correo.value || !registro.password.value || !registro.anio_nac.value) {
                alert(mensaje);
                return false; // Evitar el envío del formulario
            } else {
                return true; // El formulario es válido, puede ser enviado
            }
        }
        async function CargarModelo() {
            console.log("Cargando el modelo...");
            modelo = await tf.loadLayersModel("../red_neuronal/model.json");
            console.log("Modelo cargado :)");
        }
        function ActualizarModelo() {
            fetch('../red_neuronal/rdNeuronal.py', {
                method: 'POST', // Método POST para enviar datos al script Python si es necesario
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        function ObtenerRank() {
            if(!ValidarCampos()){
                return;
            }

            var pso = document.getElementById('peso').value;
            var altura = document.getElementById('altura').value;
            var año = document.getElementById('anio_nac').value;

            if (modelo != null) {
                var tensor = tf.tensor2d([[parseInt(pso), parseInt(altura), parseInt(año)]]);
                var prediccion = modelo.predict(tensor).dataSync();
                prediccion = parseFloat(prediccion); //Convertir texto a flotante
                prediccion = prediccion.toFixed(2); //Truncar 2 decimales
            } else {
                alert("Inténtalo de nuevo");
                return;
            }
            //alert(prediccion);
            var rank = document.getElementById('rank');
            rank.value = prediccion;

            ActualizarModelo();
        }
    </script>
        <!-- Boostrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <!--Icons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!--CSS-->
        <link rel="stylesheet" href="styles/user_registro.css">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100 ">
    <!--Contenedor del form blanco-->
    <div class="bg-white p-5 rounded-5 text-secondary " style="width: 25rem;">
        <!--Texto: Registro-->
        <div class="text-center fs-1 fw-bold">Registro</div>
            <form action="../funciones/user_insertar_sql.php" method="POST" name="registro">
                    <!--Código-->
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"> </div>
                        <input class="form-control" type="text" name="codigo" placeholder="Código UDG">
                    </div>
                    <!--Nombre-->
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"> </div>
                        <input class="form-control" type="text" name="nombre" placeholder="Nombre">
                    </div>
                    <!--Peso-->
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"></div>
                        <input class="form-control" type="number" id="peso" name="peso" placeholder="Peso" step="any">
                    </div>
                    <!--Altura-->
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"></div>
                        <input class="form-control" type="number" id="altura" name="altura" placeholder="Altura (CM)">
                    </div>
                    <!--Correo-->
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"></div>
                        <input class="form-control" type="mail" name="correo" placeholder="Correo">
                    </div>
                    <!--Password-->
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"></div>
                        <input class="form-control" type="password" name="password" placeholder="Contraseña" onblur="ValidarContrasena();">
                    </div>
                    <!--Año de nacimiento-->
                    <p class="text-center fw-bold" id="fecha_label">Año de nacimiento:</p>
                    <div class="input-group mt-1">
                        <div class="input-group-text bg-warning"></div>
                        <input class="form-control" type="number" id="anio_nac" name="anio_nac" placeholder="Año de nacimiento">
                    </div>     
                    <!--RANK-->
                    <input type="hidden" id="rank" name="rank">
                <!--Botón enviar-->
                <input type="submit" class="btn btn-danger text-white w-100 mt-4 fw-semibold shadow-sm" 
                    name="registrar" value="Enviar" onclick="ObtenerRank();"
                />                
            </form>
        <div class="d-flex gap-1 justify-content-center mt-1">
            <a href="../index.php" class="text-decoration-none text-danger fw-semibold ">Cancelar</a>
        </div>
    </div>
    <div id="resultado"></div>
</body>
</html>