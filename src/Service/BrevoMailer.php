<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;

class BrevoMailer
{
    private HttpClientInterface $client;
    private string $apiKey;
    private EntityManagerInterface $entityManager;

    // Constructeur modifié pour utiliser HttpClientInterface et EntityManagerInterface
    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->apiKey = $_ENV['BREVO_API_KEY'] ?? null; // Récupérer la clé API depuis l'environnement
        $this->entityManager = $entityManager;
    }

    public function sendEmail(string $to, string $subject, string $confirmationUrl)
    {
        // Créer le contenu HTML de l'email avec un lien cliquable
        $htmlContent = '
        <html>
            <head>
                <title>' . $subject . '</title>
            </head>
            <body>
                <p>Bienvenue sur Vimana Paris!</p>
                <p>Cliquez sur ce lien pour confirmer votre email : </p>
                <a href="' . $confirmationUrl . '">' . $confirmationUrl . '</a>
            </body>
        </html>';

        $url = "https://api.brevo.com/v3/smtp/email"; // URL de l'API pour envoyer un email

        $data = [
            "sender" => [
                "email" => "sablyl@live.fr",  // Ton email
                "name" => "Vimana Paris"      // Ton nom
            ],
            "to" => [
                [
                    "email" => $to // Email du destinataire
                ]
            ],
            "subject" => $subject,
            "htmlContent" => $htmlContent  // Envoi du contenu HTML
        ];

        try {
            // Utilisation de Symfony HttpClient pour envoyer la requête POST
            $response = $this->client->request('POST', $url, [
                'json' => $data,
                'headers' => [
                    'api-key' => $this->apiKey, // En-tête avec la clé API
                    'Content-Type' => 'application/json',
                ]
            ]);

            // Décoder la réponse
            $responseData = $response->toArray(); // Utilisation de la méthode toArray() pour obtenir le corps de la réponse sous forme de tableau

            // Enregistrement de l'email dans la base de données
            $email = new Email();
            $email->setRecipient($to);
            $email->setSubject($subject);
            $email->setContent($htmlContent);
            $email->setSentAt(new \DateTime()); // Enregistrement de la date d'envoi

            // Persister l'email dans la base de données
            $this->entityManager->persist($email);
            $this->entityManager->flush();

            // Retourner la réponse de l'API
            return $responseData;

        } catch (TransportExceptionInterface $e) {
            // Si une erreur se produit, renvoyer un message d'erreur
            return ['error' => $e->getMessage()];
        }
    }
}

