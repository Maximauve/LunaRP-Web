<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
	private string $apiUrl;
	private $userApi;

	public function __construct(UserApiService $userApiService, string $apiUrl)
	{
		$this->apiUrl = $apiUrl;
		$this->userApi = $userApiService;
	}

	#[Route('/register', name: 'app_register', methods: ['GET'])]
	public function index(): Response
	{
		return $this->render('registration/index.html.twig', [
			'last_credentials' => ["email" => "", "username" => ""],
		]);
	}

	#[Route('/register', name: 'app_sign_up', methods: ['POST'])]
	public function APIRegister(Request $request)
	{
		$email = $request->request->get('email');
		$username = $request->request->get('username');
		$password = $request->request->get('password');
		$file = $request->files->get('avatar');
		$form = [
			'email' => $email,
			'username' => $username,
			'password' => $password,
			'file' => DataPart::fromPath($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType()),
		];
		$formData = new FormDataPart($form);

		try {
			$data = $this->userApi->register($formData);
		} catch (\Exception $e) {
			// $err = explode("kwak", $e->getMessage());
			// foreach ($err as $error) {
			// 	$this->addFlash(
			// 		'error',
			// 		$error
			// 	);
			// }
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
			return $this->render('registration/index.html.twig', [
				'last_credentials' => ["email" => $email, "username" => $username]
			]);
		}

		// Store the JWT in local storage
		$username = $data['username'];
		$email = $data['email'];
		$id = $data['id'];
		try {
			$data = $this->userApi->login($email, $password);
		} catch (\Exception $e) {
			$this->addFlash(
				'error',
				$e->getMessage()
			);
			return $this->render('registration/index.html.twig', [
				'last_credentials' => ["email" => $email, "username" => $username]
			]);
		}
		$jwt = $data['token'];
		$role = $data['role'];
		if (isset($data["userId"]) && $data["userId"] != null) {
			$img = $this->apiUrl . "/local-files/" . $data["userId"];
		} else {
			$img = null;
		}

		$user = new User($id, $username, $email, $jwt, $role, $img);

		$request->getSession()->set('user', $user);


		// Redirect to homepage or another page
		return $this->redirectToRoute('app_home');
	}
}
