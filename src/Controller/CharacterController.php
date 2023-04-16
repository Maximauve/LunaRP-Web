<?php

namespace App\Controller;

use App\Service\CharacterApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    public function __construct(private CharacterApiService $characterApiService)
    {
        $this->characterApiService = $characterApiService;
    }

    #[Route('/character', name: 'app_character', methods: ['GET'])]
    public function index(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
            $data = $this->characterApiService->getCharacterMe($request->getSession()->get('user')->getJwt());
            if ($request->query->get('id') == null) {
                $one_character = null;
            } else {
                $one_character = $this->characterApiService->getCharacter($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            }
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
            return $this->render('character/index.html.twig', [  
                "all_characters" => null,
                'one_character' => null,
            ]);
        }

        return $this->render('character/index.html.twig', [
            'all_characters' => $data,
            'one_character' => $one_character,
        ]);
    }

    #[Route('/character/delete', name: 'app_character_delete', methods: ['GET'])]
    public function deleteCharacter(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_character');
            }
            $this->characterApiService->deleteCharacter($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
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
            return $this->redirectToRoute('app_character');
        }
        return $this->redirectToRoute('app_character');
    }

    #[Route('/character/view', name: 'app_character_view', methods: ['GET'])]
    public function viewCharacter(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } elseif ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_character');
            }
            $character = $this->characterApiService->getCharacter($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
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
            return $this->render('character/view.html.twig', [  
                'character' => null,
            ]);
        }

        return $this->render('character/view.html.twig', [
            'character' => $character,
        ]);
    }
}
