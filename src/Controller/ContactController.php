<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Event\ContactRequestEvent;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ContactController
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class ContactController extends AbstractController
{
    /**
     * @param Request $request
     * @param MailerInterface $mailer
     * @param EventDispatcherInterface $dispatcher
     * @return Response
     */

    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, MailerInterface $mailer, EventDispatcherInterface $dispatcher): Response
    {
        $data = new ContactDTO();

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            try {
                $dispatcher->dispatch(new ContactRequestEvent($data));
                $this->addFlash('success', 'Votre message a bien été envoyé');
            }catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible d\'envoyer votre email');
            }
        }
        return $this->render(
            view: 'contact/contact.html.twig',
            parameters: [
                'form' => $form
            ]
        );
    }
}
