<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
	private $userApiService;

	public function __construct(UserApiService $userApiService)
	{
		$this->userApiService = $userApiService;
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

		try {
			$data = $this->userApiService->register($username, $email, $password);
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
			return $this->render('registration/index.html.twig', [
				'last_credentials' => ["email" => $email, "username" => $username]
			]);
		}

		// Store the JWT in local storage
		$username = $data['username'];
		$email = $data['email'];
		try {
			$data = $this->userApiService->login($email, $password);
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

		$user = new User($username, $email, $jwt);

		$request->getSession()->set('user', $user);

		// Redirect to homepage or another page
		return $this->redirectToRoute('app_home');
	}
}
