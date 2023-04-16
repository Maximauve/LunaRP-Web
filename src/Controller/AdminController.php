<?php

namespace App\Controller;

use App\Service\CharacterApiService;
use App\Service\UserApiService;
use App\Service\ItemApiService;
use App\Service\LanguageApiService;
use App\Service\RaceApiService;
use App\Service\SpellApiService;
use App\Service\ClassApiService;
use App\Service\CampainApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $characterApiService;

	public function __construct(CharacterApiService $characterApiService, UserApiService $userApiService, ItemApiService $itemApiService, LanguageApiService $languageApiService, RaceApiService $raceApiService, SpellApiService $spellApiService, ClassApiService $classApiService, CampainApiService $campainApiService)
	{
		$this->characterApiService = $characterApiService;
        $this->userApiService = $userApiService;
        $this->itemApiService = $itemApiService;
        $this->languageApiService = $languageApiService;
        $this->raceApiService = $raceApiService;
        $this->spellApiService = $spellApiService;
        $this->classApiService = $classApiService;
        $this->campainApiService = $campainApiService;
	}

    #[Route('/admin', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_user', methods: ['GET'])]
    public function users(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->userApiService->getAllUser($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/users.html.twig', [  
                "all_users" => $data,
            ]);
        }

        return $this->render('admin/users.html.twig', [
            'all_users' => $data,
        ]);
    }

    #[Route('/admin/userDelete', name: 'admin_delete_user', methods: ['GET'])]
    public function deleteUser(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_user');
            }
            $this->userApiService->deleteUser($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'User deleted'
            );
            return $this->redirectToRoute('app_admin_user');
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
            return $this->redirectToRoute('app_admin_user');
        }
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

    #[Route('/admin/characterDelete', name: 'admin_delete_character', methods: ['GET'])]
    public function deleteCharacter(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_character');
            }
            $this->characterApiService->deleteCharacter($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Character deleted'
            );
            return $this->redirectToRoute('app_admin_character');
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
            return $this->redirectToRoute('app_admin_character');
        }
    }

    #[Route('/admin/item', name: 'app_admin_item', methods: ['GET'])]
    public function items(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->itemApiService->getAllItem($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/item.html.twig', [  
                "all_items" => $data,
            ]);
        }

        return $this->render('admin/item.html.twig', [
            'all_items' => $data,
        ]);
    }

    #[Route('/admin/itemDelete', name: 'admin_delete_item', methods: ['GET'])]
    public function deleteItem(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_item');
            }
            $this->itemApiService->deleteItem($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Item deleted'
            );
            return $this->redirectToRoute('app_admin_item');
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
            return $this->redirectToRoute('app_admin_item');
        }
    }

    #[Route('/admin/language', name: 'app_admin_language', methods: ['GET'])]
    public function languages(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->languageApiService->getAllLanguage($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/language.html.twig', [  
                "all_languages" => $data,
            ]);
        }

        return $this->render('admin/language.html.twig', [
            'all_languages' => $data,
        ]);
    }

    #[Route('/admin/languageDelete', name: 'admin_delete_language', methods: ['GET'])]
    public function deleteLanguage(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_language');
            }
            $this->languageApiService->deleteLanguage($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Language deleted'
            );
            return $this->redirectToRoute('app_admin_language');
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
            return $this->redirectToRoute('app_admin_language');
        }
    }

    #[Route('/admin/race', name: 'app_admin_race', methods: ['GET'])]
    public function races(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->raceApiService->getAllRace($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/race.html.twig', [  
                "all_races" => $data,
            ]);
        }

        return $this->render('admin/race.html.twig', [
            'all_races' => $data,
        ]);
    }

    #[Route('/admin/raceDelete', name: 'admin_delete_race', methods: ['GET'])]
    public function deleteRace(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_race');
            }
            $this->raceApiService->deleteRace($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Race deleted'
            );
            return $this->redirectToRoute('app_admin_race');
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
            return $this->redirectToRoute('app_admin_race');
        }
    }

        
    #[Route('/admin/spell', name: 'app_admin_spell', methods: ['GET'])]
    public function spells(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->spellApiService->getAllSpell($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/spell.html.twig', [  
                "all_spells" => $data,
            ]);
        }

        return $this->render('admin/spell.html.twig', [
            'all_spells' => $data,
        ]);
    }

    #[Route('/admin/spellDelete', name: 'admin_delete_spell', methods: ['GET'])]
    public function deleteSpell(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_spell');
            }
            $this->spellApiService->deleteSpell($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Spell deleted'
            );
            return $this->redirectToRoute('app_admin_spell');
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
            return $this->redirectToRoute('app_admin_spell');
        }
    }

    #[Route('/admin/classe', name: 'app_admin_classe', methods: ['GET'])]
    public function classes(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->classApiService->getAllClasse($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/classe.html.twig', [  
                "all_classes" => $data,
            ]);
        }

        return $this->render('admin/classe.html.twig', [
            'all_classes' => $data,
        ]);
    }

    #[Route('/admin/classeDelete', name: 'admin_delete_classe', methods: ['GET'])]
    public function deleteclasse(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_classe');
            }
            $this->classApiService->deleteClass($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Classe deleted'
            );
            return $this->redirectToRoute('app_admin_classe');
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
            return $this->redirectToRoute('app_admin_classe');
        }
    }

    #[Route('/admin/campain', name: 'app_admin_campain', methods: ['GET'])]
    public function campains(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            }
			$data = $this->campainApiService->getAllCampain($request->getSession()->get('user')->getJwt());
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
            
            return $this->render('admin/campain.html.twig', [  
                "all_campains" => $data,
            ]);
        }

        return $this->render('admin/campain.html.twig', [
            'all_campains' => $data,
        ]);
    }

    #[Route('/admin/campainDelete', name: 'admin_delete_campain', methods: ['GET'])]
    public function deletecampain(Request $request): Response
    {
        try {
            if ($request->getSession()->get('user') == null) {
                return $this->redirectToRoute('app_login');
            } else if ($request->query->get('id') == null) {
                return $this->redirectToRoute('app_admin_campain');
            }
            $this->campainApiService->deleteCampain($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
            $this->addFlash(
                'success',
                'Campain deleted'
            );
            return $this->redirectToRoute('app_admin_campain');
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
            return $this->redirectToRoute('app_admin_campain');
        }
    }
}
