<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WikiController extends AbstractController
{
	#[Route('/wiki', name: 'app_wiki')]
	public function index(): Response
	{
		return $this->redirectToRoute('app_wiki_page', [
			'page' => 'Classes',
		]);
	}
}
