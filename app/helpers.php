<?php
//$var = '["id","tipoid","identificacion","nombre","tipofac","tipodoc","numdoc","lugar","fecha","categoria","genero","cantidad","unidad","descripcion","vrunit","vrtotal","mediopago","numsoporte","fechaentrega","pvppublico","obs","urlimagen","date_new","user_new","user_update","created_at","updated_at"]';
//$var = '["id","tipoid","identificacion","nombre","tipofac","tipodoc","numdoc","lugar","fecha","categoria","genero","cantidad","unidad","descripcion","vrunit","vrtotal","mediopago","numsoporte","fechaentrega","pvppublico","obs","urlimagen","date_new","user_new","user_update","created_at","updated_at","factura","estado_id","banco_estado_id","caja2_estado_id"]';
//echo "<pre>";
//print_r(json_decode($var));
//echo json_encode(json_decode($var));
//exit();
if (!function_exists('banda_mantenimiento')){
    function banda_mantenimiento(){
        if(Auth::check()):
          if(Auth::user()->validar_permiso('dsi_developer')):
            if(isset($_COOKIE['secret_cookie'])):
              return '<a style="background-color: #d0393e;
              color: white;
              padding: 5px 5px;
              position: absolute;
              right: -45px;
              transform: rotate(45deg);
              width: 210px;
              text-align: center;
              top: 45px;
              font-weight: bold;
              font-size: 18px;
              opacity: 0.7;">DEBUG</a>';
            else:
                return '<a style="background-color: #d0393e;
                color: white;
                padding: 5px 5px;
                position: absolute;
                right: -45px;
                transform: rotate(45deg);
                width: 210px;
                text-align: center;
                top: 45px;
                font-weight: bold;
                font-size: 18px;
                opacity: 0.7;">DEV</a>';
            endif;
        endif;
      endif;
    }
}
if (!function_exists('custom_currency_format')){
    function custom_currency_format($value){
        return "$".number_format($value,0,",",".");
    }
}
if (!function_exists('custom_date_format')){
    function custom_date_format($date, $format=""){
        if($date!=""){
            if ($format=="") $format="d/m/Y h:i:s a";
            $new_date = strtotime($date);
            return date($format, $new_date);
        }else{
            return "";
        }
    }
}
    
?>