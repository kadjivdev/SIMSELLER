<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class NotificationRejetReglement extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $vente;
    private $message_html;
    private $destinataire;
    private $copies;
    private $objet;
    private $vente_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($destinataire,$objet,$message_html,$vente,$copies=null)
    {
        $this->$vente = $vente;
        $this->vente_id = $vente->id;
        $this->user = Auth::user();
        $this->message_html = $message_html;
        $this->copies = $copies;
        $this->destinataire = $destinataire;
        $this->objet = $objet;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->destinataire['email'])
        ->subject($this->objet)
        ->cc($this->copies ?:[])
        ->view('Email.validationReglement')
        ->with([
            'user'=>$this->user,
            'vente'=>$this->vente_id,
            'destinataire'=>$this->destinataire,
            'message_html'=>$this->message_html,
            'copies'=>$this->copies,
            'statut'=> 1
        ]);
    }
}
