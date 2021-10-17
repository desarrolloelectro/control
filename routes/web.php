<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect()->route('login');
});




Route::get('/institucion','InstitucionController@index')->name('institucion.index');

Route::get('/usuarios','UsuarioController@index')->name('usuarios.index');
Route::get('/usuarios/show/{id}','UsuarioController@show')->where('id','[0-9]+')->name('usuarios.show');
Route::get('/usuarios/nuevo','UsuarioController@create')->name('usuarios.create');
Route::post('/usuarios/guardar','UsuarioController@store')->name('usuarios.store');
Route::get('/usuarios/{id}/editar','UsuarioController@edit')->name('usuarios.edit');
Route::put('/usuarios/actualizar/{id}','UsuarioController@update')->name('usuarios.update');
Route::delete('/usuarios/eliminar/{id}','UsuarioController@destroy')->name('usuarios.destroy');
Route::get('/usuarios/importar','UsuarioController@importar')->name('usuarios.importar');
Route::post('/usuarios/subir_archivo','UsuarioController@subir_archivo')->name('usuarios.subir_archivo');

Route::put('/usuarios/actualizar_perfil/{id}','UsuarioController@actualizar_perfil')->name('usuarios.actualizar_perfil');
Route::get('/usuarios/perfil','UsuarioController@perfil')->name('usuarios.perfil');

Route::post('/usuarios/registrar_ajax','UsuarioController@registrar_ajax')->name('usuarios.registrar_ajax');

Route::get('/cotizaciones','CotizacionController@index')->name('cotizaciones.index');
Route::get('/cotizaciones/show/{id}','CotizacionController@show')->where('id','[0-9]+')->name('cotizaciones.show');
Route::get('/cotizaciones/nuevo','CotizacionController@create')->name('cotizaciones.create');
Route::post('/cotizaciones/guardar','CotizacionController@store')->name('cotizaciones.store');
Route::get('/cotizaciones/{id}/editar','CotizacionController@edit')->name('cotizaciones.edit');
Route::put('/cotizaciones/actualizar/{id}','CotizacionController@update')->name('cotizaciones.update');
Route::delete('/cotizaciones/eliminar/{id}','CotizacionController@destroy')->name('cotizaciones.destroy');
Route::post('/cotizaciones/eliminar_cotizacion','CotizacionController@eliminar_cotizacion')->name('cotizaciones.eliminar_cotizacion');
Route::post('/cotizaciones/eliminar_gasto','CotizacionController@eliminar_gasto')->name('cotizaciones.eliminar_gasto');
Route::post('/cotizaciones/cargar_tipo_gastos','CotizacionController@cargar_tipo_gastos')->name('cotizaciones.cargar_tipo_gastos');
Route::post('/cotizaciones/cargar_cotizaciones','CotizacionController@cargar_cotizaciones')->name('cotizaciones.cargar_cotizaciones');
Route::post('/cotizaciones/buscar','CotizacionController@buscar')->name('cotizaciones.buscar');

Route::get('/cotizaciones/enviar/{id}','CotizacionController@enviar')->name('cotizaciones.enviar');
Route::get('/cotizaciones/script_gastos','CotizacionController@script_gastos')->name('cotizaciones.script_gastos');
Route::get('/cotizaciones/script_revertir','CotizacionController@script_revertir')->name('cotizaciones.script_revertir');

Route::get('/gastos','GastoController@index')->name('gastos.index');
Route::get('/gastos/show/{id}','GastoController@show')->where('id','[0-9]+')->name('gastos.show');
Route::get('/gastos/nuevo','GastoController@create')->name('gastos.create');
Route::post('/gastos/guardar','GastoController@store')->name('gastos.store');
Route::get('/gastos/{id}/editar','GastoController@edit')->name('gastos.edit');
Route::put('/gastos/actualizar/{id}','GastoController@update')->name('gastos.update');
Route::delete('/gastos/eliminar/{id}','GastoController@destroy')->name('gastos.destroy');
Route::post('/gastos/cargar_tipo_gastos','GastoController@cargar_tipo_gastos')->name('gastos.cargar_tipo_gastos');
Route::post('/gastos/eliminar_gasto','GastoController@eliminar_gasto')->name('gastos.eliminar_gasto');



Route::get('/cajas','CajaController@index')->name('cajas.index');
Route::get('/cajas/show/{id}','CajaController@show')->where('id','[0-9]+')->name('cajas.show');
Route::get('/cajas/nuevo','CajaController@create')->name('cajas.create');
Route::post('/cajas/guardar','CajaController@store')->name('cajas.store');
Route::get('/cajas/{id}/editar','CajaController@edit')->name('cajas.edit');
Route::put('/cajas/actualizar/{id}','CajaController@update')->name('cajas.update');
Route::delete('/cajas/eliminar/{id}','CajaController@destroy')->name('cajas.destroy');

Route::get('/areas','AreaController@index')->name('areas.index');
Route::get('/areas/show/{id}','AreaController@show')->where('id','[0-9]+')->name('areas.show');
Route::get('/areas/nuevo','AreaController@create')->name('areas.create');
Route::post('/areas/guardar','AreaController@store')->name('areas.store');
Route::get('/areas/{id}/editar','AreaController@edit')->name('areas.edit');
Route::put('/areas/actualizar/{id}','AreaController@update')->name('areas.update');
Route::delete('/areas/eliminar/{id}','AreaController@destroy')->name('areas.destroy');

Route::get('/informe_ventas','Informe_ventaController@index')->name('informe_ventas.index');
Route::get('/informe_ventas/show/{id}','Informe_ventaController@show')->where('id','[0-9]+')->name('informe_ventas.show');
Route::get('/informe_ventas/nuevo','Informe_ventaController@create')->name('informe_ventas.create');
Route::post('/informe_ventas/guardar','Informe_ventaController@store')->name('informe_ventas.store');
Route::get('/informe_ventas/{id}/editar','Informe_ventaController@edit')->name('informe_ventas.edit');
Route::put('/informe_ventas/actualizar/{id}','Informe_ventaController@update')->name('informe_ventas.update');
Route::delete('/informe_ventas/eliminar/{id}','Informe_ventaController@destroy')->name('informe_ventas.destroy');
Route::get('/informe_ventas/importar','Informe_ventaController@importar')->name('informe_ventas.importar');
Route::post('/informe_ventas/subir_archivo','Informe_ventaController@subir_archivo')->name('informe_ventas.subir_archivo');

Route::get('/dia_ivas','Dia_ivaController@index')->name('dia_ivas.index');
Route::get('/dia_ivas/show/{id}','Dia_ivaController@show')->where('id','[0-9]+')->name('dia_ivas.show');
Route::get('/dia_ivas/nuevo','Dia_ivaController@create')->name('dia_ivas.create');
Route::post('/dia_ivas/guardar','Dia_ivaController@store')->name('dia_ivas.store');
Route::get('/dia_ivas/{id}/editar','Dia_ivaController@edit')->name('dia_ivas.edit');
Route::put('/dia_ivas/actualizar/{id}','Dia_ivaController@update')->name('dia_ivas.update');
Route::delete('/dia_ivas/eliminar/{id}','Dia_ivaController@destroy')->name('dia_ivas.destroy');
Route::get('/dia_ivas/importar','Dia_ivaController@importar')->name('dia_ivas.importar');
Route::get('/dia_ivas/script_fechas','Dia_ivaController@script_fechas')->name('dia_ivas.script_fechas');
Route::post('/dia_ivas/subir_archivo','Dia_ivaController@subir_archivo')->name('dia_ivas.subir_archivo');

Route::get('/tipo_pagos','Tipo_pagoController@index')->name('tipo_pagos.index');
Route::get('/tipo_pagos/show/{id}','Tipo_pagoController@show')->where('id','[0-9]+')->name('tipo_pagos.show');
Route::get('/tipo_pagos/nuevo','Tipo_pagoController@create')->name('tipo_pagos.create');
Route::post('/tipo_pagos/guardar','Tipo_pagoController@store')->name('tipo_pagos.store');
Route::get('/tipo_pagos/{id}/editar','Tipo_pagoController@edit')->name('tipo_pagos.edit');
Route::put('/tipo_pagos/actualizar/{id}','Tipo_pagoController@update')->name('tipo_pagos.update');
Route::delete('/tipo_pagos/eliminar/{id}','Tipo_pagoController@destroy')->name('tipo_pagos.destroy');

Route::get('/tipo_facturas','Tipo_facturaController@index')->name('tipo_facturas.index');
Route::get('/tipo_facturas/show/{id}','Tipo_facturaController@show')->where('id','[0-9]+')->name('tipo_facturas.show');
Route::get('/tipo_facturas/nuevo','Tipo_facturaController@create')->name('tipo_facturas.create');
Route::post('/tipo_facturas/guardar','Tipo_facturaController@store')->name('tipo_facturas.store');
Route::get('/tipo_facturas/{id}/editar','Tipo_facturaController@edit')->name('tipo_facturas.edit');
Route::put('/tipo_facturas/actualizar/{id}','Tipo_facturaController@update')->name('tipo_facturas.update');
Route::delete('/tipo_facturas/eliminar/{id}','Tipo_facturaController@destroy')->name('tipo_facturas.destroy');

Route::get('/tipo_documentos','Tipo_documentoController@index')->name('tipo_documentos.index');
Route::get('/tipo_documentos/show/{id}','Tipo_documentoController@show')->where('id','[0-9]+')->name('tipo_documentos.show');
Route::get('/tipo_documentos/nuevo','Tipo_documentoController@create')->name('tipo_documentos.create');
Route::post('/tipo_documentos/guardar','Tipo_documentoController@store')->name('tipo_documentos.store');
Route::get('/tipo_documentos/{id}/editar','Tipo_documentoController@edit')->name('tipo_documentos.edit');
Route::put('/tipo_documentos/actualizar/{id}','Tipo_documentoController@update')->name('tipo_documentos.update');
Route::delete('/tipo_documentos/eliminar/{id}','Tipo_documentoController@destroy')->name('tipo_documentos.destroy');

Route::get('/categorias','CategoriaController@index')->name('categorias.index');
Route::get('/categorias/show/{id}','CategoriaController@show')->where('id','[0-9]+')->name('categorias.show');
Route::get('/categorias/nuevo','CategoriaController@create')->name('categorias.create');
Route::post('/categorias/guardar','CategoriaController@store')->name('categorias.store');
Route::get('/categorias/{id}/editar','CategoriaController@edit')->name('categorias.edit');
Route::put('/categorias/actualizar/{id}','CategoriaController@update')->name('categorias.update');
Route::delete('/categorias/eliminar/{id}','CategoriaController@destroy')->name('categorias.destroy');

Route::get('/generos','GeneroController@index')->name('generos.index');
Route::get('/generos/show/{id}','GeneroController@show')->where('id','[0-9]+')->name('generos.show');
Route::get('/generos/nuevo','GeneroController@create')->name('generos.create');
Route::post('/generos/guardar','GeneroController@store')->name('generos.store');
Route::get('/generos/{id}/editar','GeneroController@edit')->name('generos.edit');
Route::put('/generos/actualizar/{id}','GeneroController@update')->name('generos.update');
Route::delete('/generos/eliminar/{id}','GeneroController@destroy')->name('generos.destroy');

Route::get('/unidades','UnidadController@index')->name('unidades.index');
Route::get('/unidades/show/{id}','UnidadController@show')->where('id','[0-9]+')->name('unidades.show');
Route::get('/unidades/nuevo','UnidadController@create')->name('unidades.create');
Route::post('/unidades/guardar','UnidadController@store')->name('unidades.store');
Route::get('/unidades/{id}/editar','UnidadController@edit')->name('unidades.edit');
Route::put('/unidades/actualizar/{id}','UnidadController@update')->name('unidades.update');
Route::delete('/unidades/eliminar/{id}','UnidadController@destroy')->name('unidades.destroy');

Route::get('/medio_pagos','Medio_pagoController@index')->name('medio_pagos.index');
Route::get('/medio_pagos/show/{id}','Medio_pagoController@show')->where('id','[0-9]+')->name('medio_pagos.show');
Route::get('/medio_pagos/nuevo','Medio_pagoController@create')->name('medio_pagos.create');
Route::post('/medio_pagos/guardar','Medio_pagoController@store')->name('medio_pagos.store');
Route::get('/medio_pagos/{id}/editar','Medio_pagoController@edit')->name('medio_pagos.edit');
Route::put('/medio_pagos/actualizar/{id}','Medio_pagoController@update')->name('medio_pagos.update');
Route::delete('/medio_pagos/eliminar/{id}','Medio_pagoController@destroy')->name('medio_pagos.destroy');

Route::get('/empresas','EmpresaController@index')->name('empresas.index');
Route::get('/empresas/show/{id}','EmpresaController@show')->where('id','[0-9]+')->name('empresas.show');
Route::get('/empresas/nuevo','EmpresaController@create')->name('empresas.create');
Route::post('/empresas/guardar','EmpresaController@store')->name('empresas.store');
Route::get('/empresas/{id}/editar','EmpresaController@edit')->name('empresas.edit');
Route::put('/empresas/actualizar/{id}','EmpresaController@update')->name('empresas.update');
Route::delete('/empresas/eliminar/{id}','EmpresaController@destroy')->name('empresas.destroy');

Route::get('/bancos','BancoController@index')->name('bancos.index');
Route::get('/bancos/show/{id}','BancoController@show')->where('id','[0-9]+')->name('bancos.show');
Route::get('/bancos/nuevo','BancoController@create')->name('bancos.create');
Route::post('/bancos/guardar','BancoController@store')->name('bancos.store');
Route::get('/bancos/{id}/editar','BancoController@edit')->name('bancos.edit');
Route::put('/bancos/actualizar/{id}','BancoController@update')->name('bancos.update');
Route::delete('/bancos/eliminar/{id}','BancoController@destroy')->name('bancos.destroy');
Route::post('/gastos/cargar_bancos','BancoController@cargar_bancos')->name('bancos.cargar_bancos');

Route::get('/envio_correos','Envio_correoController@index')->name('envio_correos.index');
Route::get('/envio_correos/show/{id}','Envio_correoController@show')->where('id','[0-9]+')->name('envio_correos.show');
Route::get('/envio_correos/nuevo','Envio_correoController@create')->name('envio_correos.create');
Route::post('/envio_correos/guardar','Envio_correoController@store')->name('envio_correos.store');
Route::get('/envio_correos/{id}/editar','Envio_correoController@edit')->name('envio_correos.edit');
Route::put('/envio_correos/actualizar/{id}','Envio_correoController@update')->name('envio_correos.update');
Route::delete('/envio_correos/eliminar/{id}','Envio_correoController@destroy')->name('envio_correos.destroy');

Route::get('/agencias','AgenciaController@index')->name('agencias.index');
Route::get('/agencias/show/{id}','AgenciaController@show')->where('id','[0-9]+')->name('agencias.show');
Route::get('/agencias/nuevo','AgenciaController@create')->name('agencias.create');
Route::post('/agencias/guardar','AgenciaController@store')->name('agencias.store');
Route::get('/agencias/{id}/editar','AgenciaController@edit')->name('agencias.edit');
Route::put('/agencias/actualizar/{id}','AgenciaController@update')->name('agencias.update');
Route::delete('/agencias/eliminar/{id}','AgenciaController@destroy')->name('agencias.destroy');

Route::get('/cotizacion_estados','Cotizacion_estadoController@index')->name('cotizacion_estados.index');
Route::get('/cotizacion_estados/show/{id}','Cotizacion_estadoController@show')->where('id','[0-9]+')->name('cotizacion_estados.show');
Route::get('/cotizacion_estados/nuevo','Cotizacion_estadoController@create')->name('cotizacion_estados.create');
Route::post('/cotizacion_estados/guardar','Cotizacion_estadoController@store')->name('cotizacion_estados.store');
Route::get('/cotizacion_estados/{id}/editar','Cotizacion_estadoController@edit')->name('cotizacion_estados.edit');
Route::put('/cotizacion_estados/actualizar/{id}','Cotizacion_estadoController@update')->name('cotizacion_estados.update');
Route::delete('/cotizacion_estados/eliminar/{id}','Cotizacion_estadoController@destroy')->name('cotizacion_estados.destroy');

Route::get('/gasto_estados','Gasto_estadoController@index')->name('gasto_estados.index');
Route::get('/gasto_estados/show/{id}','Gasto_estadoController@show')->where('id','[0-9]+')->name('gasto_estados.show');
Route::get('/gasto_estados/nuevo','Gasto_estadoController@create')->name('gasto_estados.create');
Route::post('/gasto_estados/guardar','Gasto_estadoController@store')->name('gasto_estados.store');
Route::get('/gasto_estados/{id}/editar','Gasto_estadoController@edit')->name('gasto_estados.edit');
Route::put('/gasto_estados/actualizar/{id}','Gasto_estadoController@update')->name('gasto_estados.update');
Route::delete('/gasto_estados/eliminar/{id}','Gasto_estadoController@destroy')->name('gasto_estados.destroy');

Route::get('/revisoria_estados','Revisoria_estadoController@index')->name('revisoria_estados.index');
Route::get('/revisoria_estados/show/{id}','Revisoria_estadoController@show')->where('id','[0-9]+')->name('revisoria_estados.show');
Route::get('/revisoria_estados/nuevo','Revisoria_estadoController@create')->name('revisoria_estados.create');
Route::post('/revisoria_estados/guardar','Revisoria_estadoController@store')->name('revisoria_estados.store');
Route::get('/revisoria_estados/{id}/editar','Revisoria_estadoController@edit')->name('revisoria_estados.edit');
Route::put('/revisoria_estados/actualizar/{id}','Revisoria_estadoController@update')->name('revisoria_estados.update');
Route::delete('/revisoria_estados/eliminar/{id}','Revisoria_estadoController@destroy')->name('revisoria_estados.destroy');

Route::get('/iva_estados','Iva_estadoController@index')->name('iva_estados.index');
Route::get('/iva_estados/show/{id}','Iva_estadoController@show')->where('id','[0-9]+')->name('iva_estados.show');
Route::get('/iva_estados/nuevo','Iva_estadoController@create')->name('iva_estados.create');
Route::post('/iva_estados/guardar','Iva_estadoController@store')->name('iva_estados.store');
Route::get('/iva_estados/{id}/editar','Iva_estadoController@edit')->name('iva_estados.edit');
Route::put('/iva_estados/actualizar/{id}','Iva_estadoController@update')->name('iva_estados.update');
Route::delete('/iva_estados/eliminar/{id}','Iva_estadoController@destroy')->name('iva_estados.destroy');

Route::get('/tipo_identificaciones','Tipo_identificacionController@index')->name('tipo_identificaciones.index');
Route::get('/tipo_identificaciones/show/{id}','Tipo_identificacionController@show')->where('id','[0-9]+')->name('tipo_identificaciones.show');
Route::get('/tipo_identificaciones/nuevo','Tipo_identificacionController@create')->name('tipo_identificaciones.create');
Route::post('/tipo_identificaciones/guardar','Tipo_identificacionController@store')->name('tipo_identificaciones.store');
Route::get('/tipo_identificaciones/{id}/editar','Tipo_identificacionController@edit')->name('tipo_identificaciones.edit');
Route::put('/tipo_identificaciones/actualizar/{id}','Tipo_identificacionController@update')->name('tipo_identificaciones.update');
Route::delete('/tipo_identificaciones/eliminar/{id}','Tipo_identificacionController@destroy')->name('tipo_identificaciones.destroy');

Route::get('/tipo_gastos','Tipo_gastoController@index')->name('tipo_gastos.index');
Route::get('/tipo_gastos/show/{id}','Tipo_gastoController@show')->where('id','[0-9]+')->name('tipo_gastos.show');
Route::get('/tipo_gastos/nuevo','Tipo_gastoController@create')->name('tipo_gastos.create');
Route::post('/tipo_gastos/guardar','Tipo_gastoController@store')->name('tipo_gastos.store');
Route::get('/tipo_gastos/{id}/editar','Tipo_gastoController@edit')->name('tipo_gastos.edit');
Route::put('/tipo_gastos/actualizar/{id}','Tipo_gastoController@update')->name('tipo_gastos.update');
Route::delete('/tipo_gastos/eliminar/{id}','Tipo_gastoController@destroy')->name('tipo_gastos.destroy');

Route::get('/tipo_gasto_agencias','Tipo_gasto_agenciaController@index')->name('tipo_gasto_agencias.index');
Route::get('/tipo_gasto_agencias/show/{id}','Tipo_gasto_agenciaController@show')->where('id','[0-9]+')->name('tipo_gasto_agencias.show');
Route::get('/tipo_gasto_agencias/nuevo','Tipo_gasto_agenciaController@create')->name('tipo_gasto_agencias.create');
Route::post('/tipo_gasto_agencias/guardar','Tipo_gasto_agenciaController@store')->name('tipo_gasto_agencias.store');
Route::get('/tipo_gasto_agencias/{id}/editar','Tipo_gasto_agenciaController@edit')->name('tipo_gasto_agencias.edit');
Route::put('/tipo_gasto_agencias/actualizar/{id}','Tipo_gasto_agenciaController@update')->name('tipo_gasto_agencias.update');
Route::delete('/tipo_gasto_agencias/eliminar/{id}','Tipo_gasto_agenciaController@destroy')->name('tipo_gasto_agencias.destroy');

Route::get('/usuario_agencias','Usuario_agenciaController@index')->name('usuario_agencias.index');
Route::get('/usuario_agencias/show/{id}','Usuario_agenciaController@show')->where('id','[0-9]+')->name('usuario_agencias.show');
Route::get('/usuario_agencias/nuevo','Usuario_agenciaController@create')->name('usuario_agencias.create');
Route::post('/usuario_agencias/guardar','Usuario_agenciaController@store')->name('usuario_agencias.store');
Route::get('/usuario_agencias/{id}/editar','Usuario_agenciaController@edit')->name('usuario_agencias.edit');
Route::put('/usuario_agencias/actualizar/{id}','Usuario_agenciaController@update')->name('usuario_agencias.update');
Route::delete('/usuario_agencias/eliminar/{id}','Usuario_agenciaController@destroy')->name('usuario_agencias.destroy');

Route::get('/roles','RolController@index')->name('roles.index');
Route::get('/roles/show/{id}','RolController@show')->where('id','[0-9]+')->name('roles.show');
Route::get('/roles/nuevo','RolController@create')->name('roles.create');
Route::post('/roles/guardar','RolController@store')->name('roles.store');
Route::get('/roles/{id}/editar','RolController@edit')->name('roles.edit');
Route::put('/roles/actualizar/{id}','RolController@update')->name('roles.update');
Route::delete('/roles/eliminar/{id}','RolController@destroy')->name('roles.destroy');

Route::get('/reportes','ReportesController@index')->name('reportes.index');

Route::get('/reportes/cotizaciones','ReportesController@cotizaciones')->name('reportes.cotizaciones');
Route::post('/reportes/cotizaciones_export','ReportesController@cotizaciones_export')->name('reportes.cotizaciones_export');
Route::get('/reportes/usuarios','ReportesController@usuarios')->name('reportes.usuarios');
Route::post('/reportes/usuarios_export','ReportesController@usuarios_export')->name('reportes.usuarios_export');
Route::get('/reportes/informe_ventas_export','ReportesController@informe_ventas_export')->name('reportes.informe_ventas_export');
Route::get('/reportes/dia_ivas_export','ReportesController@dia_ivas_export')->name('reportes.dia_ivas_export');

Route::get('/principal','PrincipalController@index')->name('principal.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
