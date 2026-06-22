<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyClient extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $name;
    public $tipo; // 'nuevo' o 'socio'
    public $codigoSocio;   // <--- NUEVO
    public $fechaNacimiento;
    public $nTarjNumb;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param string $name
     * @return void
     */

    public function __construct($token, $name, $tipo, $codigoSocio, $fechaNacimiento , $nTarjNumb)
    {
        $this->token = $token;
        $this->name  = $name;
        $this->tipo  = $tipo;
        $this->codigoSocio = $codigoSocio;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->nTarjNumb = $nTarjNumb;


        \Log::info("MAIL DEBUG: Datos recibidos en el constructor", [
        'name' => $this->name,
        'tipo_recibido' => $this->tipo,
        'codigo' => $this->nTarjNumb,
        'fecha' => $this->fechaNacimiento
        ]);

    }



    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $subject = $this->tipo === 'nuevo'
            ? 'Verificación de Cuenta y Membresía'
            : 'Verificación de Membresía';

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
public function content()
{

    $esSocioOVerificacion = in_array($this->tipo, ['socio', 'nuevo']);

    $vista = $esSocioOVerificacion
        ? 'frontend.emails.verify_socio'
        : 'frontend.emails.verify';

    \Log::info("MAIL DEBUG: Decisión de vista", [
        'tipo_final' => $this->tipo,
        'blade_seleccionado' => $vista
    ]);

    return new Content(
        view: $vista,
    );
}

    // LOG DE SALIDA: Ver qué archivo decidió usar


    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
