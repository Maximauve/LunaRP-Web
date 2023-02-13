<?php

namespace App\Controller;

use App\Security\User;
use App\Service\UserApiService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

// use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
	private $userApiService;

	public function __construct(UserApiService $userApiService)
	{
		$this->userApiService = $userApiService;
	}

	#[Route('/login', name: 'app_login', methods: ['GET'])]
	public function index(): Response
	{

		// $error = $authenticationUtils->getLastAuthenticationError();
		// $lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('login/index.html.twig', [
			// 'error' => $error,
			'last_email' => "",
		]);
	}

	#[Route('/login/auth', name: 'app_auth', methods: ['POST'])]
	public function APILogin(Request $request)
	{
		$email = $request->request->get('email');
		$password = $request->request->get('password');

		// Make API request to authenticate user
		$client = HttpClient::create();
		$response = $client->request('POST', $this->userApiService->getApiUrl() . '/users/auth/login', [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'json' => [
				'email' => $email,
				'password' => $password,
			],
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 201) {
			throw new \Exception('Error logging in');
		}

		$data = $response->toArray();

		// Store the JWT in local storage
		$username = $data['username'];
		$email = $data['email'];
		$jwt = $data['token'];

		$user = new User($username, $email, $jwt);

		$request->getSession()->set('user', $user);

		// Redirect to homepage or another page
		return $this->redirectToRoute('app_home');
	}

	#[Route('/logout', name: 'app_logout', methods: ['GET'])]
	public function logout(Request $request)
	{
		$this->addFlash('success', 'You have been logged out.');
		$request->getSession()->remove('user');
		return $this->redirectToRoute('app_home');
	}
}
