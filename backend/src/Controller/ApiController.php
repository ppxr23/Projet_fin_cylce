<?php
// src/Controller/ApiController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Feedback;
use App\Repository\UserRepository;
use App\Repository\AbsenceRepository;
use App\Repository\SanctionRepository;
use App\Repository\RetardRepository;
use App\Repository\VigieRepository;
use App\Repository\NoteRepository;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiController extends AbstractController
{

    #[Route('/api/deconnexion', name: 'api_deconnexion', methods: ['POST'])]
    public function deconnexion(UserInterface $user, EntityManagerInterface $em): JsonResponse
    {
        $newDate = new \DateTime('now', new \DateTimeZone('Indian/Antananarivo'));
        $user->setLastConnexion($newDate);

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'message' => 'Déconnexion réussie',
            'last_connexion' => $user->getLastConnexion()->format('Y-m-d H:i:s')
        ], Response::HTTP_OK);
    }

    #[Route('/api/list_users', name: 'api_list_users', methods: ['GET'])]
    public function list_users(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'firstname' => $user->getFirstname(),
                'roles' => $user->getRoles(),
                'matricule' => $user->getMatricule(),
                'statut' => $user->isActive(),
                'last_connexion' => $user->getLastConnexion()?->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/delete_user/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé'], Response::HTTP_OK);
    }

    #[Route('/api/add_user', name: 'api_add_user', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $newDate = new \DateTime('now', new \DateTimeZone('Indian/Antananarivo'));
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password'] ?? 'vivetic');
        
        $user->setEmail($data['email'] ?? '');
        $user->setRoles($data['roles'] ?? '');
        $user->setName($data['name'] ?? '');
        $user->setFirstname($data['firstname'] ?? '');
        $user->setPassword($hashedPassword);
        $user->setStatut($data['statut'] ?? '');
        $user->setDateCreation($newDate ?? '');
        $user->setLastConnexion($newDate ?? '');
        $user->setMatricule($data['matricule'] ?? '');
        $user->setVigie(0);

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur ajouté avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/update_user/{id}', name: 'api_update_user', methods: ['PUT', 'PATCH'])]
    public function updateUser( int $id, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }

        if (isset($data['statut'])) {
            $user->setStatut($data['statut']);
        }

        if (isset($data['matricule'])) {
            $user->setMatricule($data['matricule']);
        }

        $em->flush();

        return $this->json(['message' => 'Utilisateur mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/add_feedback', name: 'api_add_feedback', methods: ['POST'])]
    public function addFeedback(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $feedback = new Feedback();
        $now = new \DateTime('now', new \DateTimeZone('Indian/Antananarivo'));

        // Récupération des données envoyées depuis le frontend
        $feedback->setMatriculeConcerned($data['matricule_concerned'] ?? 0);
        $feedback->setMatriculeInsert($data['matricule_insert'] ?? 0);
        $feedback->setDateInserted($now);
        $feedback->setCritere1($data['critere_1'] ?? 0);
        $feedback->setCritere2($data['critere_2'] ?? 0);
        $feedback->setCritere3($data['critere_3'] ?? 0);
        $feedback->setCritere4($data['critere_4'] ?? 0);
        $feedback->setCritere5($data['critere_5'] ?? 0);
        $feedback->setCommentary($data['commentary'] ?? '');
        $feedback->setTypeFeedback($data['type_feedback'] ?? 0);

        $em->persist($feedback);
        $em->flush();

        return $this->json(['message' => 'Feedback ajouté avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/list_user_rh', name: 'api_list_user_rh', methods: ['POST'])]
    public function list_user_rh(UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $users = $userRepository->get_all_user_actif($data['matricule'], $data['roles'], $data['all']);
        
        return $this->json($users);
    }

    #[Route('/api/count_user_rh', name: 'api_count_user_rh', methods: ['POST'])]
    public function count_user_rh(UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_users = count($userRepository->get_all_user_actif($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_users);
    }

    #[Route('/api/count_absence_rh', name: 'api_count_absence_rh', methods: ['POST'])]
    public function count_absence_rh(AbsenceRepository $absenceRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_absences = count($absenceRepository->get_absence_today($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_absences);
    }

    #[Route('/api/count_sanction_rh', name: 'api_count_sanction_rh', methods: ['POST'])]
    public function count_sanction_rh(SanctionRepository $sanctionRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_sanctions = count($sanctionRepository->get_sanction_today($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_sanctions);
    }

    #[Route('/api/count_retard_rh', name: 'api_count_retard_rh', methods: ['POST'])]
    public function count_retard_rh(RetardRepository $retardRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_retards = count($retardRepository->get_retard_today($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_retards);
    }

    #[Route('/api/count_retard_month', name: 'api_count_retard_month', methods: ['POST'])]
    public function count_retard_month(RetardRepository $retardRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_retards_month = count($retardRepository->get_retard_month($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_retards_month);
    }

    #[Route('/api/count_sanction_month', name: 'api_count_sanction_month', methods: ['POST'])]
    public function count_sanction_month(SanctionRepository $sanctionRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_sanctions_month = count($sanctionRepository->get_sanction_month($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_sanctions_month);
    }

    #[Route('/api/count_absence_month', name: 'api_count_absence_month', methods: ['POST'])]
    public function count_absence_month(AbsenceRepository $absenceRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $count_absence_month = count($absenceRepository->get_absence_month($data['matricule'], $data['roles'], $data['all']));
        return $this->json($count_absence_month);
    }

    #[Route('/api/all_vigie', name: 'api_all_vigie', methods: ['GET'])]
    public function all_vigie(VigieRepository $vigieRepository): JsonResponse
    {
        $all_vigie = $vigieRepository->get_all_vigie();
        return $this->json($all_vigie);
    }

    #[Route('/api/all_note', name: 'api_all_note', methods: ['GET'])]
    public function all_note(NoteRepository $noteRepository): JsonResponse
    {
        $all_note = $noteRepository->get_all_notes();
        return $this->json($all_note);
    }

    #[Route('/api/all_note_team', name: 'api_all_note_team', methods: ['POST'])]
    public function all_note_team(NoteRepository $noteRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $all_notes   = $noteRepository->get_all_notes_team($data['matricule'], $data['roles'], $data['all']);
        return $this->json($all_notes);
    }

    #[Route('/api/down', name: 'api_down')]
    public function downloadExcel(NoteRepository $noteRepository, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $typeRapport = $data['params']['typeRapport'];
        $roles = $data['roles'];
        $matricule = $data['matricule'];

        $spreadsheet = new Spreadsheet();
        $sheet1 = $spreadsheet->getActiveSheet();

        if ($typeRapport == 1) {
            $data1 = $noteRepository->get_all_notes();

            $sheet1->setTitle('Performance par équipe');
            $sheet1->setCellValue('A1', 'Equipe');
            $sheet1->setCellValue('B1', 'Note');

            $row = 2;
            foreach ($data1 as $item) {
                $sheet1->setCellValue('A' . $row, $item['name']);
                $sheet1->setCellValue('B' . $row, $item['moyenne']);
                $row++;
            }

            foreach (range('A', 'B') as $col) {
                $sheet1->getColumnDimension($col)->setAutoSize(true);
            }
        } else {
            if ($roles == 'RH') {
                $data1 = $noteRepository->get_all_notes_pers();
            } else {
                $data1 = $noteRepository->get_all_notes_team($matricule, $roles);
            }

            $sheet1->setTitle('Performance par collaborateur');
            $sheet1->setCellValue('A1', 'Matricule');
            $sheet1->setCellValue('B1', 'Nom');
            $sheet1->setCellValue('C1', 'Prénom');
            $sheet1->setCellValue('D1', 'Note');

            $row = 2;
            foreach ($data1 as $item) {
                $sheet1->setCellValue('A' . $row, $item['matricule']);
                $sheet1->setCellValue('B' . $row, $item['name']);
                $sheet1->setCellValue('C' . $row, $item['firstname']);
                $sheet1->setCellValue('D' . $row, $item['moyenne']);
                $row++;
            }

            foreach (range('A', 'D') as $col) {
                $sheet1->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    #[Route('/api/all_anomalie', name: 'api_all_anomalie', methods: ['POST'])]
    public function all_anomalie(UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $all_anomalie   = $userRepository->get_anomalie($data['matricule'], $data['roles']);
        return $this->json($all_anomalie);
    }

    #[Route('/api/all_feedback', name: 'api_all_feedback', methods: ['POST'])]
    public function all_feedback(FeedbackRepository $feedbackRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $all_feedback   = $feedbackRepository->get_feedback($data['matricule'], $data['roles']);
        return $this->json($all_feedback);
    }
}
