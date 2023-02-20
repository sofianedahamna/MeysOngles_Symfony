<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountRdvController extends AbstractController
{
    #[Route('/account/rdv', name: 'app_account_rdv')]
    public function index(): Response
    {
        return $this->render('account/rdv_show.html.twig');
    }
}
