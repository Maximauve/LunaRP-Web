<?php

namespace App\Controller;

use App\Service\ClassApiService;
use App\Service\ItemApiService;
use App\Service\LanguageApiService;
use App\Service\RaceApiService;
use App\Service\SpellApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;

class WikiController extends AbstractController
{

	private $apiUrl;
	private $ClassApi;
	private $RaceApi;
	private $LanguageApi;
	private $SpellApi;
	private $ItemApi;
	private $acceptedPages = [
		"class",
		"race",
		"language",
		"spell",
		"item",
	];

	public function __construct(
		string $apiUrl,
		ClassApiService $classApiService,
		RaceApiService $raceApiService,
		LanguageApiService $languageApiService,
		SpellApiService $spellApiService,
		ItemApiService $itemApiService
	) {
		$this->apiUrl = $apiUrl;
		$this->ClassApi = $classApiService;
		$this->RaceApi = $raceApiService;
		$this->LanguageApi = $languageApiService;
		$this->SpellApi = $spellApiService;
		$this->ItemApi = $itemApiService;
	}

	private function getApiService(string $page)
	{
		switch ($page) {
			case 'class':
				return $this->ClassApi;

			case 'race':
				return $this->RaceApi;

			case 'language':
				return $this->LanguageApi;

			case 'spell':
				return $this->SpellApi;

			case 'item':
				return $this->ItemApi;

			default:
				return $this->ClassApi;
		}
	}

	#[Route('/wiki', name: 'app_wiki', methods: ['GET'])]
	public function index(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			return $this->redirectToRoute('app_login');
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
		}
		$user = $req->getSession()->get('user');

		$page = $req->query->get('page');
		$id = $req->query->get('id');
		if ($page === null || !in_array($page, $this->acceptedPages)) {
			$page = "class";
			$classes = $this->ClassApi->getAll($user->getJwt());
			$id = $classes[0]["id"];
			return $this->redirectToRoute('app_wiki', [
				'page' => $page,
				'id' => $id,
			]);
		}
		if ($id === null) {
			$apiService = $this->getApiService($page);
			if ($apiService->getAll($user->getJwt()) === []) {
				$item = null;
				$iterable = null;
				switch ($page) {
					case 'class':
						$frPage = "Classes";
						break;
					case 'race':
						$frPage = "Races";
						break;
					case 'language':
						$frPage = "Langues";
						break;
					case 'spell':
						$frPage = "Sorts";
						break;
					default:
						$frPage = "Objets";
						break;
				}
				return $this->render('wiki/wiki.html.twig', [
					'page' => $page,
					'frPage' => $frPage,
					'iterable' => $iterable,
					'item' => $item,
					'id' => $id,
				]);
			}
			$id = $apiService->getAll($user->getJwt())[0]["id"];
			return $this->redirectToRoute('app_wiki', [
				'page' => $page,
				'id' => $id,
			]);
		}

		$apiService = $this->getApiService($page);
		$iterable = $apiService->getAll($user->getJwt());
		if ($apiService instanceof ItemApiService) {
			try {
				$item = $apiService->get($user->getJwt(), $id);
			} catch (\Exception $e) {
				$item = null;
			}
			$item["img"] = $this->apiUrl . "/local-files/" . $item["itemId"];
		} else {
			$item = $apiService->get($user->getJwt(), $id);
		}
		if ($item == null) {
			throw $this->createNotFoundException("Page not found");
		}
		$ids = array_column($iterable, 'id');
		array_multisort($ids, SORT_ASC, $iterable);
		return $this->render('wiki/wiki.html.twig', [
			'page' => $page,
			'iterable' => $iterable,
			'item' => $item,
			'id' => intval($id),
		]);
	}

	#[Route('/wiki/class/create', name: 'app_wiki_class', methods: ['GET'])]
	public function creationClass(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$iterable = $this->ClassApi->getAll($req->getSession()->get('user')->getJwt());
		$ids = array_column($iterable, 'id');
		array_multisort($ids, SORT_ASC, $iterable);
		return $this->render('wiki/createClass.html.twig', [
			'page' => "class",
			'iterable' => $iterable,
			'id' => null,
			'name' => "",
			'dice' => 0,
			'description' => "",
		]);
	}

	#[Route('/wiki/class/create', name: 'app_wiki_class_create', methods: ['POST'])]
	public function createClass(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$form = [
			'name' => $req->request->get('name'),
			'dice' => $req->request->get('dice'),
			'description' => $req->request->get('description'),
		];
		try {
			$class = $this->ClassApi->create($req->getSession()->get('user')->getJwt(), $form);
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
			$iterable = $this->ClassApi->getAll($req->getSession()->get('user')->getJwt());
			$ids = array_column($iterable, 'id');
			array_multisort($ids, SORT_ASC, $iterable);
			return $this->render('wiki/createClass.html.twig', [
				'page' => "class",
				'iterable' => $iterable,
				'id' => null,
				'name' => $form['name'],
				'dice' => $form['dice'],
				'description' => $form['description'],
			]);
		}
		$this->addFlash('success', "La classe a bien été créée");

		return $this->redirectToRoute('app_wiki', [
			'page' => 'class',
			'id' => $class['id'],
		]);
	}


	#[Route('/wiki/race/create', name: 'app_wiki_race', methods: ['GET'])]
	public function creationRace(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$languageList = $this->LanguageApi->getAll($req->getSession()->get('user')->getJwt());
		$ids = array_column($languageList, 'id');
		array_multisort($ids, SORT_ASC, $languageList);
		return $this->render('wiki/createRace.html.twig', [
			"page" => "race",
			"iterable" => $languageList,
			'id' => null,
			'name' => "",
			'speed' => 0,
			'size' => "",
			'description' => "",
			'languages' => [],
			'languagesList' => $languageList,
		]);
	}

	#[Route('/wiki/race/create', name: 'app_wiki_race_create', methods: ['POST'])]
	public function createRace(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$languages = array_keys($req->request->all('languages'));

		$form = [
			'name' => $req->request->get('name'),
			'speed' => $req->request->get('speed'),
			'size' => $req->request->get('size'),
			'description' => $req->request->get('description'),
			'languages' => $languages,
		];

		try {
			$race = $this->RaceApi->create($req->getSession()->get('user')->getJwt(), $form);
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

			$languages = [];
			foreach ($form['languages'] as $language) {
				$languages[] = $this->LanguageApi->get($req->getSession()->get('user')->getJwt(), $language);
			}

			$languagesList = $this->LanguageApi->getAll($req->getSession()->get('user')->getJwt());
			$ids = array_column($languagesList, 'id');
			array_multisort($ids, SORT_ASC, $languagesList);
			return $this->render('wiki/createRace.html.twig', [
				"page" => "race",
				'name' => $form['name'],
				'iterable' => $languagesList,
				'id' => null,
				'speed' => $form['speed'],
				'size' => $form['size'],
				'description' => $form['description'],
				'languages' => $languages,
				'languagesList' => $languagesList,
			]);
		}
		return $this->redirectToRoute('app_wiki', [
			'page' => 'race',
			'id' => $race['id'],
		]);
	}


	#[Route('/wiki/language/create', name: 'app_wiki_language', methods: ['GET'])]
	public function creationLanguage(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$iterable = $this->LanguageApi->getAll($req->getSession()->get('user')->getJwt());
		$ids = array_column($iterable, 'id');
		array_multisort($ids, SORT_ASC, $iterable);
		return $this->render('wiki/createLanguage.html.twig', [
			"page" => "language",
			"iterable" => $iterable,
			'id' => null,
			'name' => "",
			'description' => "",
		]);
	}

	#[Route('/wiki/language/create', name: 'app_wiki_language_create', methods: ['POST'])]
	public function createLanguage(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$form = [
			'name' => $req->request->get('name'),
			'description' => $req->request->get('description'),
		];

		try {
			$language = $this->LanguageApi->create($req->getSession()->get('user')->getJwt(), $form);
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
			$languages = $this->LanguageApi->getAll($req->getSession()->get('user')->getJwt());
			$ids = array_column($language, 'id');
			array_multisort($ids, SORT_ASC, $languages);
			return $this->render('wiki/createLanguage.html.twig', [
				"page" => "language",
				'iterable' => $languages,
				'id' => null,
				'name' => $form['name'],
				'description' => $form['description'],
			]);
		}
		return $this->redirectToRoute('app_wiki', [
			'page' => 'language',
			'id' => $language['id'],
		]);
	}


	#[Route('/wiki/spell/create', name: 'app_wiki_spell', methods: ['GET'])]
	public function creationSpell(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$ClassesList = $this->ClassApi->getAll($req->getSession()->get('user')->getJwt());

		$iterable = $this->SpellApi->getAll($req->getSession()->get('user')->getJwt());
		$ids = array_column($iterable, 'id');
		array_multisort($ids, SORT_ASC, $iterable);
		return $this->render('wiki/createSpell.html.twig', [
			"page" => "spell",
			"iterable" => $iterable,
			"id" => null,
			"name" => "",
			"level" => 0,
			"scope" => "",
			"component" => "",
			"casting_time" => "",
			"duration" => "",
			"description" => "",
			"classes" => [],
			"classesList" => $ClassesList,
		]);
	}

	#[Route('/wiki/spell/create', name: 'app_wiki_spell_create', methods: ['POST'])]
	public function createSpell(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$classes = array_keys($req->request->all('classes'));

		$form = [
			"name" => $req->request->get('name'),
			"level" => $req->request->get('level'),
			"scope" => $req->request->get('scope'),
			"component" => $req->request->get('component'),
			"casting_time" => $req->request->get('casting_time'),
			"duration" => $req->request->get('duration'),
			"description" => $req->request->get('description'),
			"classes" => $classes,
		];

		try {
			$spell = $this->SpellApi->create($req->getSession()->get('user')->getJwt(), $form);
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

			$classes = [];
			foreach ($form['classes'] as $class) {
				$classes[] = $this->ClassApi->get($req->getSession()->get('user')->getJwt(), $class);
			}
			$ClassesList = $this->ClassApi->getAll($req->getSession()->get('user')->getJwt());

			$iterable = $this->SpellApi->getAll($req->getSession()->get('user')->getJwt());
			$ids = array_column($iterable, 'id');
			array_multisort($ids, SORT_ASC, $iterable);
			return $this->render('wiki/createSpell.html.twig', [
				"page" => "spell",
				"iterable" => $iterable,
				"id" => null,
				"name" => $form['name'],
				"level" => $form['level'],
				"scope" => $form['scope'],
				"component" => $form['component'],
				"casting_time" => $form['casting_time'],
				"duration" => $form['duration'],
				"description" => $form['description'],
				"classes" => $classes,
				"classesList" => $ClassesList,
			]);
		}
		return $this->redirectToRoute('app_wiki', [
			'page' => 'spell',
			'id' => $spell['id'],
		]);
	}


	#[Route('/wiki/item/create', name: 'app_wiki_item', methods: ['GET'])]
	public function creationItem(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$iterable = $this->ItemApi->getAll($req->getSession()->get('user')->getJwt());
		$ids = array_column($iterable, 'id');
		array_multisort($ids, SORT_ASC, $iterable);
		return $this->render('wiki/createItem.html.twig', [
			"page" => "item",
			"iterable" => $iterable,
			"id" => null,
			"name" => "",
			"price" => 0,
			"damages" => 0,
			"defense" => 0,
			"regeneration" => 0,
			"description" => "",
		]);
	}

	#[Route('/wiki/item/create', name: 'app_wiki_item_create', methods: ['POST'])]
	public function createItem(Request $req): Response
	{
		if ($req->getSession()->get('user') === null) {
			$this->addFlash('error', "Vous devez être connecté pour accéder à cette page");
			return $this->redirectToRoute('app_login');
		}

		$form = [
			"name" => $req->request->get('name'),
			"price" => $req->request->get('price'),
			"damages" => $req->request->get('damages'),
			"defense" => $req->request->get('defense'),
			"regeneration" => $req->request->get('regeneration'),
			"description" => $req->request->get('description'),
		];
		$file = $req->files->get('avatar');
		if ($file !== null) {
			$form['file'] = DataPart::fromPath($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType());
		}

		$formData = new FormDataPart($form);

		try {
			$item = $this->ItemApi->create($req->getSession()->get('user')->getJwt(), $formData);
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
			$iterable = $this->ItemApi->getAll($req->getSession()->get('user')->getJwt());
			$ids = array_column($iterable, 'id');
			array_multisort($ids, SORT_ASC, $iterable);
			return $this->render('wiki/createItem.html.twig', [
				"page" => "item",
				"iterable" => $iterable,
				"id" => null,
				"name" => $form['name'],
				"price" => $form['price'],
				"damages" => $form['damages'],
				"defense" => $form['defense'],
				"regeneration" => $form['regeneration'],
				"description" => $form['description'],
			]);
		}
		return $this->redirectToRoute('app_wiki', [
			'page' => 'item',
			'id' => $item['id'],
		]);
	}
}
