<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Degree;
use App\Entity\File;
use App\Entity\Rating;
use App\Entity\Staff;
use App\Entity\Subject;
use App\Entity\University;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('NoteKeeper Admin Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Universities', 'fa fa-building', University::class);
        yield MenuItem::linkToCrud('Degrees', 'fa fa-book', Degree::class);
        yield MenuItem::linkToCrud('Subjects', 'fa fa-bookmark', Subject::class);
        yield MenuItem::linkToCrud('Ratings', 'fa fa-star', Rating::class);
        yield MenuItem::linkToCrud('Files', 'fa fa-file', File::class);
        yield MenuItem::linkToCrud('Course', 'fa fa-list-ol', Course::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Staff', 'fa fa-user-shield', Staff::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setAvatarUrl('https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50');
    }


}
