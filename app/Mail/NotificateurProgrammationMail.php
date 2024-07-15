<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class NotificateurProgrammationMail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $programmations;
    private $message_html;
    private $destataire;
    private $copies;
    private $lienAction;
    private $objet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($destataire,$objet,$message_html,$copies=null,$programmations=null,$lienAction=null)
    {
        $this->programmations = $programmations;
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
            ->view('Email.programmation')
            ->with([
                'user'=>$this->user,
                'programmations'=>$this->programmations,
                'destinataire'=>$this->destataire,
                'message_html'=>$this->message_html,
                'copies'=>$this->copies,
                'lienAction'=>$this->lienAction

            ]);
    }
}
