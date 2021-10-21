<?php
//$var = '["id","tipoid","identificacion","nombre","tipofac","tipodoc","numdoc","lugar","fecha","categoria","genero","cantidad","unidad","descripcion","vrunit","vrtotal","mediopago","numsoporte","fechaentrega","pvppublico","obs","urlimagen","date_new","user_new","user_update","created_at","updated_at"]';
//$var = '["id","tipoid","identificacion","nombre","tipofac","tipodoc","numdoc","lugar","fecha","categoria","genero","cantidad","unidad","descripcion","vrunit","vrtotal","mediopago","numsoporte","fechaentrega","pvppublico","obs","urlimagen","date_new","user_new","user_update","created_at","updated_at","factura","estado_id","banco_estado_id","caja2_estado_id"]';
//echo "<pre>";
//print_r(json_decode($var));
//echo json_encode(json_decode($var));
//exit();
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