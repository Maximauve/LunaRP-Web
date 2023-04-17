<?php

namespace App\Controller;

use App\Service\CharacterApiService;
use App\Service\ItemApiService;
use App\Service\LocalfileApiService;
use App\Service\RaceApiService;
use App\Service\ClassApiService;
use App\Service\SpellApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
	public function __construct(private CharacterApiService $characterApiService, private ItemApiService $itemApiService, private LocalfileApiService $localfileApiService, private RaceApiService $raceApiService, private ClassApiService $classApiService, private SpellApiService $spellApiService)
	{
		$this->characterApiService = $characterApiService;
		$this->itemApiService = $itemApiService;
		$this->localfileApiService = $localfileApiService;
		$this->raceApiService = $raceApiService;
		$this->classApiService = $classApiService;
		$this->spellApiService = $spellApiService;
	}

	#[Route('/character', name: 'app_character', methods: ['GET'])]
	public function index(Request $request): Response
	{
		try {
			if ($request->getSession()->get('user') == null) {
				return $this->redirectToRoute('app_login');
			}
			$data = $this->characterApiService->getCharacterMe($request->getSession()->get('user')->getJwt());
			foreach ($data as $i => $character) {
				if ($character["characterId"] !== null) {
					$img = $this->localfileApiService->getImage($character["characterId"]);
					$data[$i]["img"] = $img;
				} else {
					$data[$i]["img"] = null;
				}
			}
			if ($request->query->get('id') == null) {
				$one_character = null;
			} else {
				$one_character = $this->characterApiService->get($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
				if ($one_character["characterId"] !== null) {
					$one_character["img"] = $this->localfileApiService->getImage($one_character["characterId"]);
				} else {
					$one_character["img"] = null;
				}
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
			$this->characterApiService->delete($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
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
			$character = $this->characterApiService->get($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
			if ($character["characterId"] !== null) {
				$character["img"] = $this->localfileApiService->getImage($character["characterId"]);
			} else {
				$character["img"] = null;
			}
			foreach ($character["inventory"] as $i => $item) {
				if ($item["item"]["itemId"] != null) {
					$item["item"]["img"] = $this->localfileApiService->getImage($item["item"]["itemId"]);
					$character["inventory"][$i] = $item;
				} else {
					$item["item"]["img"] = null;
					$character["inventory"][$i] = $item;
				}
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
			return $this->render('character/view.html.twig', [
				'character' => null,
			]);
		}

		return $this->render('character/view.html.twig', [
			'character' => $character,
		]);
	}

	#[Route('/character/create', name: 'app_character_create', methods: ['GET'])]
	public function createCharacter(Request $request): Response
	{
		try {
			if ($request->getSession()->get('user') == null) {
				return $this->redirectToRoute('app_login');
			}
			$races = $this->raceApiService->getAll($request->getSession()->get('user')->getJwt());
			$classes = $this->classApiService->getAll($request->getSession()->get('user')->getJwt());
			$items = $this->itemApiService->getAll($request->getSession()->get('user')->getJwt());
			$spells = $this->spellApiService->getAll($request->getSession()->get('user')->getJwt());
			foreach ($items as $i => $item) {
				if ($item["itemId"] != null) {
					$item["img"] = $this->localfileApiService->getImage($item["itemId"]);
					$items[$i] = $item;
				} else {
					$item["img"] = null;
					$items[$i] = $item;
				}
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
			return $this->render('character/create.html.twig', [
				'all_races' => array(),
				'all_classes' => array(),
				'all_items' => array(),
				'all_spells' => array(),
			]);
		}
		return $this->render('character/create.html.twig', [
			"all_races" => $races,
			"all_classes" => $classes,
			"all_items" => $items,
			"all_spells" => $spells,
		]);
	}

	#[Route('/character/createCharacter', name: 'app_character_createCharacter', methods: ['POST'])]
	public function createCharacterPost(Request $request): Response
	{

		$name = $request->request->get('name');
		$race = $request->request->get('race');
		$classe = $request->request->get('classe');
		$alignment = $request->request->get('alignment');
		$description = $request->request->get('description');
		$level = $request->request->get('niveau');
		$experience = $request->request->get('experience');
		$force = $request->request->get('force');
		$dexterite = $request->request->get('dexterite');
		$constitution = $request->request->get('constitution');
		$intelligence = $request->request->get('intelligence');
		$sagesse = $request->request->get('sagesse');
		$charisme = $request->request->get('charisme');
		$file = $request->files->get('avatar');
		$items = array_keys($request->request->all('item'));
		if ($items == null) {
			$items = array();
		}
		$spells = array_keys($request->request->all('spell'));
		if ($spells == null) {
			$spells = array();
		}
		$form = [
			'name' => $name,
			'race' => $race,
			'classe' => $classe,
			'alignment' => $alignment,
			'description' => $description,
			'level' => $level,
			'experience' => $experience,
			'strength' => $force,
			'dexterity' => $dexterite,
			'constitution' => $constitution,
			'intelligence' => $intelligence,
			'wisdom' => $sagesse,
			'charisma' => $charisme,
			'inventory' => $items,
			'spells' => $spells,
			'file' => DataPart::fromPath($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType()),
		];
		$formData = new FormDataPart($form);
		try {
			$this->characterApiService->create($request->getSession()->get('user')->getJwt(), $formData);
			return $this->redirectToRoute('app_character');
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
			return $this->redirectToRoute('app_character_create');
		}
	}

	#[Route('/character/edit', name: 'app_character_edit', methods: ['GET'])]
	public function editCharacter(Request $request): Response
	{
		try {
			if ($request->getSession()->get('user') == null) {
				return $this->redirectToRoute('app_login');
			} else if ($request->query->get('id') == null) {
				return $this->redirectToRoute('app_character');
			}
			$races = $this->raceApiService->getAll($request->getSession()->get('user')->getJwt());
			$classes = $this->classApiService->getAll($request->getSession()->get('user')->getJwt());
			$items = $this->itemApiService->getAll($request->getSession()->get('user')->getJwt());
			$spells = $this->spellApiService->getAll($request->getSession()->get('user')->getJwt());
			foreach ($items as $i => $item) {
				if ($item["itemId"] != null) {
					$item["img"] = $this->localfileApiService->getImage($item["itemId"]);
					$items[$i] = $item;
				} else {
					$item["img"] = null;
					$items[$i] = $item;
				}
			}
			$one_character = $this->characterApiService->get($request->getSession()->get('user')->getJwt(), $request->query->get('id'));
			dump($one_character);
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
			return $this->render('character/edit.html.twig', [
				'all_races' => array(),
				'all_classes' => array(),
				'all_items' => array(),
				'all_spells' => array(),
			]);
		}
		return $this->render('character/edit.html.twig', [
			"all_races" => $races,
			"all_classes" => $classes,
			"all_items" => $items,
			"all_spells" => $spells,
			"one_character" => $one_character,
		]);
	}

	#[Route('/character/editCharacter', name: 'app_character_editCharacter', methods: ['POST'])]
	public function editCharacterPost(Request $request): Response
	{

		$name = $request->request->get('name');
		$race = $request->request->get('race');
		$classe = $request->request->get('classe');
		$alignment = $request->request->get('alignment');
		$description = $request->request->get('description');
		$level = $request->request->get('niveau');
		$experience = $request->request->get('experience');
		$force = $request->request->get('force');
		$dexterite = $request->request->get('dexterite');
		$constitution = $request->request->get('constitution');
		$intelligence = $request->request->get('intelligence');
		$sagesse = $request->request->get('sagesse');
		$charisme = $request->request->get('charisme');
		$file = $request->files->get('avatar');
		$items = array_keys($request->request->all('item'));
		$spells = array_keys($request->request->all('spell'));
		$form = [
			'name' => $name,
			'race' => $race,
			'classe' => $classe,
			'alignment' => $alignment,
			'description' => $description,
			'level' => $level,
			'experience' => $experience,
			'strength' => $force,
			'dexterity' => $dexterite,
			'constitution' => $constitution,
			'intelligence' => $intelligence,
			'wisdom' => $sagesse,
			'charisma' => $charisme,
			'inventory' => $items,
			'spells' => $spells,
			'file' => DataPart::fromPath($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType()),
		];
		$formData = new FormDataPart($form);
		try {
			$this->characterApiService->create($request->getSession()->get('user')->getJwt(), $formData);
			return $this->redirectToRoute('app_character');
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
			return $this->redirectToRoute('app_character_create');
		}
	}
}
