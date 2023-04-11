<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_user')]
    public function users(): Response
    {
        return $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/character', name: 'app_admin_character')]
    public function characters(): Response
    {
        return $this->render('admin/character.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/item', name: 'app_admin_item')]
    public function items(): Response
    {
        return $this->render('admin/item.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/language', name: 'app_admin_language')]
    public function languages(): Response
    {
        return $this->render('admin/language.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/race', name: 'app_admin_race')]
    public function races(): Response
    {
        return $this->render('admin/race.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
        
    #[Route('/admin/spell', name: 'app_admin_spell')]
    public function spells(): Response
    {
        return $this->render('admin/spell.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/classe', name: 'app_admin_classe')]
    public function classes(): Response
    {
        return $this->render('admin/classe.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

}
