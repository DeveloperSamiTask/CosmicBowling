<?php
    include("inc.var.php");
    /* include("modelo2.php"); */

    if(isset($_SESSION['session_socio'])){
        $id=$_SESSION['session_socio'];
        $socio = new cls_emp_socio($id);
        $cupon = new cls_emp_cupon(1);
    }else{
        header("location:frm_login.php");
    }
    date_default_timezone_set("America/New_York");
?>

<!doctype html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="css/estilos.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="imgclub/favicon.ico">
    <script src="https://kit.fontawesome.com/c51b016b55.js" crossorigin="anonymous"></script>
    <title>Promociones Socio</title>
</head>
<script src="/javascripts/application.js" type="text/javascript" charset="utf-8" async defer>
    function enviar (pagina) {
        var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=530, height=280, top=85, left=140";
        var i;
        for (i=0;i<document.form1.rb_cupon.length;i++){
            if (document.form1.rb_cupon[i].checked)
                break;
        }
        cupon = document.form1.rb_cupon[i].value ;
        window.open(pagina+"?cupon="+cupon,"",opciones);
    }
</script>
<style type="text/css">
    .container, .row {
        height: 100%;
        min-height: 100%;
    }

    @font-face {
        font-family: 'riffic_freebold';
        src: url('fonts/rifficfree-bold-webfont.woff2') format('woff2'),
             url('fonts/rifficfree-bold-webfont.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    }

    .textotitulo{
        font-family:'riffic_freebold', sans-serif;
        font-weight: 200;
        font-size: 26px;
    }

    .textotitulo2{
        font-family:'riffic_freebold', sans-serif;
        font-weight: 100;
        font-size: 22px;
    }

    .navtitulo{
        font-family: 'riffic_freebold', sans-serif;
        font-weight: 100;
        font-size: 16px;
    }

    .navtitulo a:hover{
        font-family: 'riffic_freebold', sans-serif;
        color: #fff;
    }

    body{
        height: 100%;
        background:
        url("imgclub/Pantalla.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
    }

    html {}

    .cupon1{
        margin-top: 110px;
    }
</style>
<body>
    <script type="text/javascript">
        // const btn = document.querySelector('#imprimir1');
        // btn.addEventListener('click', saludar);

        // document.getElementsById("imprimir1").addEventListener("click", function(){
        //   var div = document.getElementsById("first1");
        //   imprimirElemento(div);
        // });

        // document.querySelector('#imprimir1').addEventListener('click', function() {
        // var div = document.querySelector('#first1');
        // imprimirElemento(div);
        // });

        // document.querySelector("#imprimir2").addEventListener("click", function() {
        //   var div = document.querySelector("#first2");
        //   imprimirElemento(div);
        // });

        //  document.querySelector("#imprimir3").addEventListener("click", function() {
        //   var div = document.querySelector("#first3");
        //   imprimirElemento(div);
        // });
    </script>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top ">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="imgclub/logo.png" width="80" height="80" alt="">
                <span class="name"> Hola, <?php echo $_SESSION['session_name'];?> </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link navtitulo " href="socios.php">DATOS DE SOCIO <span class="sr-only"></span></a>
                        </li>
                        <!--<li class="nav-item ">
                        <a class="nav-link navtitulo" href="tarjeta.php">DATOS DE TARJETA <span class="sr-only"></span></a>
                        </li>  -->
                        <li class="nav-item ">
                            <a class="nav-link navtitulo" href="renueva.php">RENUEVA TU MEMBRESÍA <span class="sr-only"></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navtitulo active" href="promosocio.php">PROMOCIONES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navtitulo" href="cerrarsesion.php">SALIR</a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container cupon1">

        <p align="center" class="text-h1" style=" font-family: 'riffic_freebold', sans-serif; background-color:  #0E7205; margin-bottom: 20px;">Imprime tus Cupones</p>
      <div class="row justify-content-center align-items-center cupon2">
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-body">
                        <!-- <img src="images/socios.png" height="30" width="30" align="left" style="margin-right: 8px;" ><h6 class="card-title"> Imprime tus Cupones</h6> -->
                        <td>
                            <?php
                                $img1=verCupon('1',$id,$cupon->get_txt_val1());
                                $img2=verCupon('2',$id,$cupon->get_txt_val2());
                                $img3=verCupon('3',$id,$cupon->get_txt_val3());
                                $img4=verCupon('4',$id,$cupon->get_txt_val4());
                                $img5=verCupon('5',$id,$cupon->get_txt_val5());
                                $st=SemActual(FillSem(date("Y-m-d")),$id);
                                //echo "Estas en la semana ".FillSem(date("Y-m-d"))." de ".date("d/m/Y")."<br>";
                                // echo "Tienes ".$st." Impresi&oacute;n esta semana";
                            ?>
                        </td>

                		<?php
                			include("modelo.php");
                		?>
                        <!-- CUPÓN NUMERO 1  -->
                      <form action="" method="post">
                            <div class="tx_boddy3" id="first1">
                              	<img src="upload/cupon1-Marzo.jpg" width="800" height="400"/>
                            </div>
                            <input type="hidden" name="socio" value="<?php echo $_SESSION['session_socio'];?>">
                            <input type="hidden" name="cupon" value="10">
                            <?php
                                $select = "SELECT * FROM cupones_granja WHERE estado = '1'";
                                $result = mysqli_query($database,$select);
                                while($cap = mysqli_fetch_array($result)){
                                    $dato = "SELECT COUNT(*) FROM print_cupon WHERE print = '20' and id_cliente = '$id' ";
                                    $resultado = mysqli_query($database,$dato);
                                    $total = mysqli_fetch_assoc($resultado);
                                    $fill = $total['COUNT(*)'];
                                    if ($fill == 0){
                            ?>
                                        <button type="submit" class="btn btn-secondary btn-block" name="enviar1" onclick="myFunction1();" style="color: #fff;">IMPRIMIR CUPON</button>
                                    <?}else{?>
                                        <button type="button" class="btn btn-secondary btn-block" disabled>IMPRIMIR CUPON</button>
                                    <?}
                                }
                            ?>
                        </form>
                        <br>
                        <?php
                       		if (isset($_POST['enviar1'])) {
                       			include("modelo.php");
                       			$socio = $_POST['socio'];
                       			$cupon = $_POST['cupon'];
                       			$insertar = "INSERT INTO print_cupon(id_cliente, print, fecha_print, estado) VALUES ('$socio', '$cupon', CURDATE(), '0')";
                       			$resultado = mysqli_query($database,$insertar);
                                if (!$resultado) {
                                    echo "Error";
                                }else{
                                    header("location: promosocio.php");
                                }
            				}
                        ?>
                        <!-- FIN CUPÓN NUMERO 1  -->
                         <!-- CUPÓN NUMERO 2 -->
                    <form action="" method="post">
                            <div class="tx_boddy3" id="first2">
                                <img src="upload/cupon2-Marzo.jpg" width="800" height="400"/>
                            </div>
                            <input type="hidden" name="socio" value="<?php echo $_SESSION['session_socio'];?>">
                            <input type="hidden" name="cupon" value="20">
                            <?php
                                $select = "SELECT * FROM cupones_granja WHERE estado = '1'";
                                $result = mysqli_query($database,$select);
                                while($cap = mysqli_fetch_array($result)){
                                    $dato = "SELECT COUNT(*) FROM print_cupon WHERE print = '30' and id_cliente = '$id' ";
                                    $resultado = mysqli_query($database,$dato);
                                    $total = mysqli_fetch_assoc($resultado);
                                    $fill = $total['COUNT(*)'];
                                    if ($fill == 0){
                            ?>
                                        <button type="submit" class="btn btn-secondary btn-block" name="enviar2" onclick="myFunction2()" style="color: #fff;">IMPRIMIR CUPON</button>
                                    <?}else{?>
                                        <button type="button" class="btn btn-secondary btn-block" disabled>IMPRIMIR CUPON</button>
                                    <?}
                                }
                            ?>
                        </form>
                        <br>
                        <?php
                            if (isset($_POST['enviar2'])) {
                                include("modelo.php");
                                $socio = $_POST['socio'];
                                $cupon = $_POST['cupon'];
                                $insertar = "INSERT INTO print_cupon(id_cliente, print, fecha_print, estado) VALUES ('$socio', '$cupon', CURDATE(), '0')";
                                $resultado = mysqli_query($database,$insertar);
                                if (!$resultado) {
                                    echo "Error";
                                }else{
                                    header("location: promosocio.php");
                                }
                            }
                        ?>
                        <!-- FIN CUPÓN NUMERO 2  -->
                        <!-- CUPÓN NUMERO 3
                         <form action="" method="post">
                            <div class="tx_boddy3" id="first3">
                                <img src="upload/cupon3.jpg" width="800" height="400"/>
                            </div>
                            <input type="hidden" name="socio" value="<?php echo $_SESSION['session_socio'];?>">
                            <input type="hidden" name="cupon" value="30">
                            <?php
                                $select = "SELECT * FROM cupones_granja WHERE estado = '1'";
                                $result = mysqli_query($database,$select);
                                while($cap = mysqli_fetch_array($result)){
                                    $dato = "SELECT COUNT(*) FROM print_cupon WHERE print = '30' and id_cliente = '$id' ";
                                    $resultado = mysqli_query($database,$dato);
                                    $total = mysqli_fetch_assoc($resultado);
                                    $fill = $total['COUNT(*)'];
                                    if ($fill == 0){
                            ?>
                                        <button type="submit" class="btn btn-secondary btn-block" name="enviar3" onclick="myFunction3()" style="color: #fff;">IMPRIMIR CUPON</button>
                                    <?}else{?>
                                        <button type="button" class="btn btn-secondary btn-block" disabled>IMPRIMIR CUPON</button>
                                    <?}
                                }
                            ?>
                        </form>
                        <?php
                            if (isset($_POST['enviar3'])) {
                                include("modelo.php");
                                $socio = $_POST['socio'];
                                $cupon = $_POST['cupon'];
                                $insertar = "INSERT INTO print_cupon(id_cliente, print, fecha_print, estado) VALUES ('$socio', '$cupon', CURDATE(), '0')";
                                $resultado = mysqli_query($database,$insertar);
                                if (!$resultado) {
                                    echo "Error";
                                }else{
                                    header("location: promosocio.php");
                                }
                            }
                        ?>
                  FIN CUPÓN NUMERO 2  -->
                        <!-- FIN CUPÓN NUMERO 3  -->
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="names_cli" value="<?= $_SESSION['session_name']  ?>" >
                <input type="hidden" id="code_cli" value="<?= $_SESSION['session_tarjeta']  ?>" >
  </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        function myFunction1(){
            var div = document.querySelector("#first1");
            imprimirElemento(div);
        }
    </script>
    <script>
        function myFunction2(){
            var div = document.querySelector("#first2");
            imprimirElemento(div);
        }
    </script>
        <script>
        function myFunction3(){
            var div = document.querySelector("#first3");
            imprimirElemento(div);
        }
    </script>
    <script>

        function imprimirElemento (elemento) {
            var names = document.getElementById('names_cli').value;
            var code = document.getElementById('code_cli').value;
            var ventana = window.open('', 'PRINT', 'height=400,width=800');
            ventana.document.write('<html><head><title>' + document.title + '</title>');
            ventana.document.write('<link rel="stylesheet" href="style.css">');
            ventana.document.write('</head><body>');
            ventana.document.write(elemento.innerHTML);
            ventana.document.write("<br><br> <h2>&nbsp;&nbsp;" + names+ " - " + code + "</h2>");
            ventana.document.write();
            ventana.document.write('</body></html>');
            ventana.document.close();
            ventana.focus();
            ventana.onload = function () {
                ventana.print();

            }
            return true;
        }
    </script>
</body>
</html>
