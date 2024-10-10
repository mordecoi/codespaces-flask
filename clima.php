<?php

//En la variable $apiKey de la línea 13 cada alumno debería obtener su appid una vez que se registra en la página de la api
// 



//LLAMADA A LA API
//============================================================================================================
$status="";
$msg="";
$city="";
$apiKey="49c0bad2c7458f1c76bec9654081a661";
if(isset($_POST['submit'])){                              //Si se hizo submit (osea se buscó una ciudad), entonces...
    $city=$_POST['city'];                                 //$city toma el nombre de la ciudad
    $url="http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey";   //defino la url de la api
    $ch=curl_init();                                     //inicio curl - curl=comunicación por URL
    curl_setopt($ch,CURLOPT_URL,$url);                   //le pasamos la url que queremos capturar
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);        //CURLOPT_RETURNTRANSFER=true para que nos permita devolver el "resultado de la transferencia" cuando hacemos curl_exec(), en vez de mostrarlo directamente. El valor será algo como: '{"cod":404,"message":'City not found'}'
    $result=curl_exec($ch);                              //alamcenamos en $result el string mencionado anteriormente 
    curl_close($ch);                                     //cerramos el curl (la comunicación)
    $result=json_decode($result,true);                   /* transformamos el result a formato JSON= result{
                                                                                                            ["cod"] => 404
                                                                                                            ["message"] => 'City not found'
                                                                                                            ...}
                                                                                                    */
    if($result['cod']==200){                             //si el código es correcto (osea igual a 200).. 
        $status="yes";                                   //seteamos $status a yes
    }else{                                               //si no..
        $msg=$result['message'];                         //seteamos a $msg el mensaje de result 
    }
}


?>

<!--  HEAD Y ESTILO  /////////////////////////////////////////////////////////////////////////////////////////////////-->

<html lang="en" class=" -webkit-">
   <head>
      <meta charset="UTF-8">
      <title>Weather API</title>
      <style>
         @import url(https://fonts.googleapis.com/css?family=Poiret+One);
         @import url(https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.min.css);
         body {
         background-color: cadetblue;
         font-family: Poiret One;
         }
         .widget {
         position: absolute;
         top: 50%;
         left: 50%;
         display: flex;
         height: 300px;
         width: 600px;
         transform: translate(-50%, -50%);
         flex-wrap: wrap;
         cursor: pointer;
         border-radius: 20px;
         box-shadow: 0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15);
         }
         .widget .weatherIcon {
         flex: 1 100%;
         height: 60%;
         border-top-left-radius: 20px;
         border-top-right-radius: 20px;
         background: #FAFAFA;
         font-family: weathericons;
         display: flex;
         align-items: center;
         justify-content: space-around;
         font-size: 100px;
         }
         .widget .weatherIcon i {
         padding-top: 30px;
         }
         .widget .weatherInfo {
         flex: 0 0 70%;
         height: 40%;
         background: darkslategray;
         border-bottom-left-radius: 20px;
         display: flex;
         align-items: center;
         color: white;
         }
         .widget .weatherInfo .temperature {
         flex: 0 0 40%;
         width: 100%;
         font-size: 65px;
         display: flex;
         justify-content: space-around;
         }
         .widget .weatherInfo .description {
         flex: 0 60%;
         display: flex;
         flex-direction: column;
         width: 100%;
         height: 100%;
         justify-content: center;
         margin-left:-15px;
         }
         .widget .weatherInfo .description .weatherCondition {
         text-transform: uppercase;
         font-size: 35px;
         font-weight: 100;
         }
         .widget .weatherInfo .description .place {
         font-size: 15px;
         }
         .widget .date {
         flex: 0 0 30%;
         height: 40%;
         background: #70C1B3;
         border-bottom-right-radius: 20px;
         display: flex;
         justify-content: space-around;
         align-items: center;
         color: white;
         font-size: 30px;
         font-weight: 800;
         }
         p {
         position: fixed;
         bottom: 0%;
         right: 2%;
         }
         p a {
         text-decoration: none;
         color: #E4D6A7;
         font-size: 10px;
         }
         .form{
         position: absolute;
         top: 42%;
         left: 50%;
         display: flex;
         height: 300px;
         width: 600px;
         transform: translate(-50%, -50%);
         }
         .text{
         width: 80%;
         padding: 10px
         }
         .submit{
         height: 39px;
         width: 100px;
         border: 0px;
         }
         .mr45{
             margin-right:45px;
         }
      </style>
   </head>

   
<!--  BODY //////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--  BODY //////////////////////////////////////////////////////////////////////////////////////////////////////////-->
   <body>
      <div class="form">
         <form style="width:100%;" method="post">
            <input type="text" class="text" placeholder="Enter city name" name="city" value="<?php echo $city?>"/>
            <input type="submit" value="Consultar" class="submit" name="submit"/>
            <?php echo $msg?>    <!--  Mostramos $msg, si encontramos la ciudad el mensaje es nulo, por lo cual no se muestra-->
         </form>
      </div>
      <?php if($status=="yes"){?>    <!--  Si el status es "yes", osea encontramos la ciudad, mostramos todo el contenido  -->
      <article class="widget">
         <div class="weatherIcon">
            <img src="http://openweathermap.org/img/wn/<?php echo $result['weather'][0]['icon']?>@4x.png"/>
         </div>
         <div class="weatherInfo">
            <div class="temperature">
               <span><?php echo round($result['main']['temp']-273.15)?>°</span>    <!--  Resto  273.15 grados para obtener en celcius ya que por defecto es en Kelvinº -->
            </div>
            <div class="description mr45">
               <div class="weatherCondition"><?php echo $result['weather'][0]['main']?></div>
               <div class="place"><?php echo $result['name']?></div>
            </div>
            <div class="description">
               <div class="weatherCondition">Wind</div>
               <div class="place"><?php echo $result['wind']['speed']?> M/H</div>
            </div>
         </div>
         <div class="date">
            <?php echo date('d M',$result['dt'])?>              
         </div>
      </article>
      <?php } ?>
   </body>
</html>


