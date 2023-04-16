<?php

namespace App\Controller;

use App\Service\UserApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	private UserApiService $userApi;
	private string $apiUrl;

	public function __construct(UserApiService $userApiService, string $apiUrl)
	{
		$this->userApi = $userApiService;
		$this->apiUrl = $apiUrl;
	}

	#[Route('/profile', name: 'app_profile', methods: ['GET'])]
	public function index(Request $req): Response
	{
		if (!$req->getSession()->get('user')) {
			$this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
			return $this->redirectToRoute('app_login');
		}
		$user = $req->getSession()->get('user');
		return $this->render('user/index.html.twig', []);
	}

	#[Route('/profile/edit', name: 'app_profile_update', methods: ['POST'])]
	public function update(Request $req): Response
	{
		if (!$req->getSession()->get('user')) {
			$this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
			return $this->redirectToRoute('app_login');
		}
		$user = $req->getSession()->get('user');


		$email = $req->request->get('email');
		$username = $req->request->get('username');
		$password = $req->request->get('password');
		$file = $req->files->get('avatar');
		$form = [
			'id' => strval($user->getId()),
			'email' => $email,
			'username' => $username,
		];
		if ($password != "") {
			$form['password'] = $password;
		}
		if ($file != null) {
			$form['file'] = DataPart::fromPath($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType());
		}
		$formData = new FormDataPart($form);

		try {
			$response = $this->userApi->update($user->getJwt(), $formData);
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
			return $this->redirectToRoute('app_profile');
		}
		$updatedUser = $this->userApi->get($user->getJwt(), $user->getId());
		$updatedUser->setProfilePicture($this->apiUrl . $updatedUser->getProfilePicture());

		$req->getSession()->set('user', $updatedUser);
		$this->addFlash('success', 'Votre profil a bien été mis à jour.');
		return $this->redirectToRoute('app_profile');
	}
}
