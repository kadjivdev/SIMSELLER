<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CommandeMail extends Mailable
{
    use Queueable, SerializesModels;
    private $message_html;
    private $destataire;
    private $copies;
    private $typemail;
    private $lienAction;
    private $objet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($destataire,$objet,$message_html,$copies=null,$lienAction=null)
    {
        $this->user = Auth::user();
        $this->message_html = $message_html;
        $this->copies = $copies;
        $this->destataire = $destataire;
        $this->objet = $objet;
        $this->lienAction = $lienAction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->destataire['email'])
            ->subject($this->objet)
            ->cc($this->copies ?:[])
            ->view('Email.commande')
            ->with([
                'user'=>$this->user,
                'destinataire'=>$this->destataire,
                'message_html'=>$this->message_html,
                'copies'=>$this->copies,
                'lienAction'=>$this->lienAction
            ]);
    }
}
