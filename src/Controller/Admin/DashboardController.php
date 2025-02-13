<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Email;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    // Injection du EntityManagerInterface
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {    
        // Récupérer le service depuis le conteneur
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Générer l'URL vers la gestion des utilisateurs
        $url = $adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();

        // Récupérer les derniers utilisateurs inscrits (par exemple, les 5 derniers)
        $recentUsers = $this->entityManager->getRepository(User::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);  // Trier par date de création (ajuster selon le nom du champ)

        // Récupérer les derniers emails envoyés (par exemple, les 5 derniers)
        $recentEmails = $this->entityManager->getRepository(Email::class)
            ->findBy([], ['sentAt' => 'DESC'], 5);  // Trier par date d'envoi

        return $this->render('admin/dashboard.html.twig', [
            'recentUsers' => $recentUsers,
            'recentEmails' => $recentEmails,  // Ajouter cette ligne pour passer les emails à la vue
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Vimana Agence');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Emails envoyés', 'fas fa-envelope', Email::class);  // Ajouter cette ligne pour les emails
    }
}
