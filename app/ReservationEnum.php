<?php

namespace App;

enum ReservationEnum : string
{
    case Payee = 'Payée';
    case Annule = 'Annulée';
    case EnCours = 'En cours...';
    case Termine = 'Terminée !';
}
