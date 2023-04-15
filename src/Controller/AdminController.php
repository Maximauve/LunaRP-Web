<?php

namespace App\Controller;

use App\Service\CharacterApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $characterApiService;

	public function __construct(CharacterApiService $characterApiService)
	{
		$this->characterApiService = $characterApiService;
	}

    #[Route('/admin', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_user', methods: ['GET'])]
    public function users(): Response
    {
        return $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/character', name: 'app_admin_character', methods: ['GET'])]
    public function characters(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->characterApiService->getAllCharacter($request->getSession()->get('user')->getJwt());
		} catch (\Exception $e) {
            $data = null;
			$error = explode("ERR", $e->getMessage());
			if (count($error) == 1) {
				$this->addFlash(
					'error',
					$error[0]
				);
			} else {
				foreach ($error as $err) {
					$this->addFlash(
						'error',
						$err
					);
				}
			}
            
            return $this->render('admin/character.html.twig', [  
                "all_characters" => $data,
            ]);
        }

        return $this->render('admin/character.html.twig', [
            'all_characters' => $data,
        ]);
    }

    #[Route('/admin/item', name: 'app_admin_item', methods: ['GET'])]
    public function items(): Response
    {
        return $this->render('admin/item.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/language', name: 'app_admin_language', methods: ['GET'])]
    public function languages(): Response
    {
        return $this->render('admin/language.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/race', name: 'app_admin_race', methods: ['GET'])]
    public function races(): Response
    {
        return $this->render('admin/race.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
        
    #[Route('/admin/spell', name: 'app_admin_spell', methods: ['GET'])]
    public function spells(): Response
    {
        return $this->render('admin/spell.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/classe', name: 'app_admin_classe', methods: ['GET'])]
    public function classes(): Response
    {
        return $this->render('admin/classe.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

}
