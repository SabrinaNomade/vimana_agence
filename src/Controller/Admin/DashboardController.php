<?php
namespace App\Controller\Admin;

// Importation des entités User et Email pour les gérer dans le tableau de bord
use App\Entity\User;
use App\Entity\Email;

// Importation des classes nécessaires d'EasyAdmin pour la configuration du tableau de bord
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

// Importation des classes Symfony pour la gestion des routes et des réponses HTTP
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Importation de Doctrine pour interagir avec la base de données
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    // Injection du EntityManagerInterface pour interagir avec la base de données
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Route pour afficher le tableau de bord administrateur
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {    
        // Récupérer le service AdminUrlGenerator depuis le conteneur de services
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Générer l'URL vers la gestion des utilisateurs dans EasyAdmin
        $url = $adminUrlGenerator
            ->setController(UserCrudController::class)  // Spécifie le contrôleur des utilisateurs
            ->generateUrl();  // Génère l'URL pour la page de gestion des utilisateurs

        // Récupérer les derniers utilisateurs inscrits (ici les 5 derniers créés)
        $recentUsers = $this->entityManager->getRepository(User::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);  // Trier par date de création (ajuste selon le champ)

        // Récupérer les derniers emails envoyés (ici les 5 derniers envoyés)
        $recentEmails = $this->entityManager->getRepository(Email::class)
            ->findBy([], ['sentAt' => 'DESC'], 5);  // Trier par date d'envoi

        // Renvoyer la réponse en utilisant le template Twig 'dashboard.html.twig'
        // On passe les derniers utilisateurs et emails à la vue pour les afficher dans le tableau de bord
        return $this->render('admin/dashboard.html.twig', [
            'recentUsers' => $recentUsers,  // Passer les utilisateurs à la vue
            'recentEmails' => $recentEmails,  // Passer les emails à la vue
        ]);
    }

    // Cette méthode configure les paramètres du tableau de bord, comme le titre
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Vimana Agence');  // Titre du tableau de bord
    }

    // Cette méthode configure le menu de navigation du tableau de bord
    public function configureMenuItems(): iterable
    {
        // Ajouter un lien vers le tableau de bord
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        // Ajouter un lien vers la gestion des utilisateurs (lien vers le CRUD User)
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        
        // Ajouter un lien vers la gestion des emails envoyés (lien vers le CRUD Email)
        yield MenuItem::linkToCrud('Emails envoyés', 'fas fa-envelope', Email::class);  // Lien pour les emails envoyés
    }
}

