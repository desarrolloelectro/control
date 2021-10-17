<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Cotizacion;
use App\Usuario;
use App\Cotizacion_detalle;

class EnviarEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $cotizacion;
    private $fecha_envio;
    private $usuario;
    private $cotizacion_detalle;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cotizacion $cotizacion,$fecha_envio,$usuario,$cotizacion_detalle)
    {
        //
        $this->cotizacion = $cotizacion;
        $this->fecha_envio = $fecha_envio;
        $this->usuario = $usuario;
        $this->cotizacion_detalle = $cotizacion_detalle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        return $this->view('emails.enviar')
        ->from('autorizacioncontrol@gmail.com','Toolset Control')
        ->subject('Autorización Toolset Control #'.$this->cotizacion->id." :: ".$this->fecha_envio)
        ->with([
            'cotizacion' => $this->cotizacion,
            'fecha_envio' => $this->fecha_envio,
            'usuario' => $this->usuario,
            'cotizacion_detalle' => $this->cotizacion_detalle,
        ]);
    }
}
