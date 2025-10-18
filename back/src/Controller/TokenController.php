<?php
// src/Controller/TokenController.php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class TokenController extends AbstractController
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/token/refresh', name: 'api_token_refresh', methods: ['POST'])]
    public function refresh(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['refresh_token'])) {
            return $this->json(['error' => 'Refresh token requis'], 400);
        }

        try {
            // Décoder le refresh token (tu peux aussi le stocker en base)
            $refreshToken = $data['refresh_token'];
            
            // Ici tu peux vérifier si le refresh token est valide
            // (en base de données par exemple)
            
            // Pour cet exemple, on assume qu'on a l'email dans le refresh token
            // Dans la vraie vie, tu stockerais les refresh tokens en base
            $email = $data['email'] ?? null;
            
            if (!$email) {
                return $this->json(['error' => 'Email requis pour le refresh'], 400);
            }

            $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['email' => $email]);

            if (!$user) {
                throw new UserNotFoundException();
            }

            // Créer un nouveau token
            $newToken = $this->jwtManager->create($user);
            
            return $this->json([
                'token' => $newToken,
                'expires_in' => 3600, // 1 heure
                'message' => 'Token renouvelé avec succès ! 🔄'
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Refresh token invalide'], 401);
        }
    }

    #[Route('/api/token/validate', name: 'api_token_validate', methods: ['GET'])]
    public function validate(Request $request): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json(['error' => 'Token invalide ou expiré'], 401);
        }

        return $this->json([
            'valid' => true,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName()
            ],
            'message' => 'Token valide ! ✅'
        ]);
    }

    #[Route('/api/token/info', name: 'api_token_info', methods: ['GET'])]
    public function tokenInfo(Request $request): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->json(['error' => 'Token manquant'], 400);
        }

        $token = substr($authHeader, 7);
        
        try {
            // Décoder le token pour voir ses infos (sans vérifier la signature)
            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                throw new \Exception('Token malformé');
            }

            $payload = json_decode(base64_decode($tokenParts[1]), true);
            
            $now = time();
            $exp = $payload['exp'] ?? 0;
            
            $timeLeft = $exp - $now;
            
            return $this->json([
                'expires_at' => date('Y-m-d H:i:s', $exp),
                'expires_in_seconds' => max(0, $timeLeft),
                'expires_in_minutes' => max(0, round($timeLeft / 60)),
                'is_expired' => $timeLeft <= 0,
                'user' => $payload['username'] ?? 'inconnu',
                'issued_at' => date('Y-m-d H:i:s', $payload['iat'] ?? 0)
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Impossible de lire le token'], 400);
        }
    }
}  