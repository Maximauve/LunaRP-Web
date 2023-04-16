<?php

namespace App\Controller;

use App\Service\CharacterApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
	private $caracterApi;

	public function __construct(CharacterApiService $caracterApiService)
	{
		$this->caracterApi = $caracterApiService;
	}

	#[Route('/character', name: 'app_character')]
	public function index(Request $request): Response
	{
		if ($request->getSession()->get('user') === null) {
			$this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
			return $this->redirectToRoute('app_login');
		}
		return $this->render('character/index.html.twig', [
			'controller_name' => 'CharacterController',
		]);
	}
}
