<?php

namespace App;

enum CarEnum : string
{
    case Reservee = 'Reservée';
    case Disponible = 'Disponible !';
    case Enlocation = 'En location...';
    case EnMaintenance = 'En maintenance...';
    case EnReparation = 'En réparation...';


}
