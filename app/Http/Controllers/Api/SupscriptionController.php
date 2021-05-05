<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\SupscriptionInterface;

class SupscriptionController extends Controller
{

    private $SupscriptionInterface;

    public function __construct(SupscriptionInterface $SupscriptionInterface)
    {

        $this->SupscriptionInterface = $SupscriptionInterface;
    }

    public function LimitSupscriptions()
    {

        return $this->SupscriptionInterface->LimitSupscriptions();
    }

    public function ClosedSupscriptions()
    {

        return $this->SupscriptionInterface->LimitSupscriptions();
    }

    
}
