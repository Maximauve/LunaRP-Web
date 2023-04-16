<?php

namespace App\Controller;

use App\Service\CharacterApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	public function __construct(private CharacterApiService $characterApiService)
    {
        $this->characterApiService = $characterApiService;
    }

	#[Route('/', name: 'app_home')]
	public function index(Request $request): Response
	{
		if ($request->getSession()->get('user') === null) {
			return $this->redirectToRoute('app_login');
		}
		try {
			if ($request->getSession()->get('user') == null) {
				return $this->redirectToRoute('app_login');
			}
			$data = $this->characterApiService->getCharacterMe($request->getSession()->get('user')->getJwt());
		} catch (\Exception $e) {
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
			return $this->render('home/index.html.twig', [  
				"all_characters" => null,
			]);
		}

		return $this->render('home/index.html.twig', [
			'all_characters' => $data,
		]);
	}
}
