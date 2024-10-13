<?php
// src/Event/InvitationEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Form\Model\InvitationModel;

class InvitationEvent extends Event
{
    public const NAME = 'invitation.send';

    private $invitationModel;

    public function __construct(InvitationModel $invitationModel)
    {
        $this->invitationModel = $invitationModel;
    }

    public function getInvitationModel(): InvitationModel
    {
        return $this->invitationModel;
    }
}
