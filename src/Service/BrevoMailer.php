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

    // Nouvelle méthode pour envoyer un email du formulaire de contact
    public function sendContactEmail(string $nom, string $email, string $message)
    {
        // Créer le contenu HTML de l'email pour le formulaire de contact
        $htmlContent = '
        <html>
            <head>
                <title>Message de contact - ' . $nom . '</title>
            </head>
            <body>
                <p><strong>Nom:</strong> ' . $nom . '</p>
                <p><strong>Email:</strong> ' . $email . '</p>
                <p><strong>Message:</strong><br/>' . nl2br($message) . '</p>
            </body>
        </html>';

        $url = "https://api.brevo.com/v3/smtp/email"; // URL de l'API pour envoyer un email

        // Paramètres de l'email
        $data = [
            "sender" => [
                "email" => "$email",  // Ton email
                "name" => "$nom"      // Ton nom
            ],
            "to" => [
                [
                    "email" => "berrandousabrina@gmail.com" // Email de l'administrateur
                ]
            ],
            "subject" => "Nouveau message de contact de " . $nom,
            "htmlContent" => $htmlContent  // Contenu HTML de l'email
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

            // Enregistrer l'email dans la base de données (si tu veux garder une trace)
            $emailEntity = new Email();
            $emailEntity->setRecipient("berrandousabrina@gmail.com");
            $emailEntity->setSubject("Nouveau message de contact de " . $nom);
            $emailEntity->setContent($htmlContent);
            $emailEntity->setSentAt(new \DateTime()); // Enregistrement de la date d'envoi

            // Persister l'email dans la base de données
            $this->entityManager->persist($emailEntity);
            $this->entityManager->flush();

            // Retourner la réponse de l'API
            return $responseData;

        } catch (TransportExceptionInterface $e) {
            // Si une erreur se produit, renvoyer un message d'erreur
            return ['error' => $e->getMessage()];
        }
    }
    public function sendPasswordResetEmail(string $to, string $resetUrl)
{
    $htmlContent = '
    <html>
        <head>
            <title>Réinitialisation de votre mot de passe</title>
        </head>
        <body>
            <p>Bonjour,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour le réinitialiser :</p>
            <a href="' . $resetUrl . '">' . $resetUrl . '</a>
            <p>Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer ce message.</p>
        </body>
    </html>';

    $url = "https://api.brevo.com/v3/smtp/email"; // URL de l'API pour envoyer un email

    // Paramètres de l'email
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
        "subject" => "Réinitialisation de votre mot de passe",
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

        // Vérification du code de statut de la réponse pour s'assurer que l'API est bien autorisée
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200 && $statusCode !== 201) {
            throw new \RuntimeException('Erreur lors de l\'envoi de l\'email de réinitialisation : ' . $statusCode);
        }

        // Décoder la réponse
        $responseData = $response->toArray(); // Utilisation de la méthode toArray() pour obtenir le corps de la réponse sous forme de tableau

        // Enregistrer l'email dans la base de données
        $email = new Email();
        $email->setRecipient($to);
        $email->setSubject("Réinitialisation de votre mot de passe");
        $email->setContent($htmlContent);
        $email->setSentAt(new \DateTime()); // Enregistrement de la date d'envoi

        // Persister l'email dans la base de données
        $this->entityManager->persist($email);
        $this->entityManager->flush();

        // Retourner la réponse de l'API
        return $responseData;

    } catch (TransportExceptionInterface $e) {
        // Si une erreur se produit, renvoyer un message d'erreur
        return ['error' => 'Erreur de transport : ' . $e->getMessage()];
    } catch (\RuntimeException $e) {
        // Si une erreur spécifique à l'API se produit, renvoyer un message d'erreur
        return ['error' => 'Erreur de l\'API : ' . $e->getMessage()];
    }
}
}