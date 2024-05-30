<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class MailingSubscriber
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class MailingSubscriber implements EventSubscriberInterface
{
    /**
     * @param MailerInterface $mailer
     */
    public function __construct(private MailerInterface $mailer){}

    /**
     * @param ContactRequestEvent $event
     * @return void
     * @throws TransportExceptionInterface
     */
    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        $data = $event->data;
        $mail = (new TemplatedEmail())
            ->to($data->service)
            ->from($data->email)
            ->subject('Demande de contact')
            ->htmlTemplate('/contact/contact.html.twig')
            ->context(['data' => $data]);
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof User){
            return;
        }
        $mail = (new Email())
            ->to($user->getEmail())
            ->from('support@demo.fr')
            ->subject('Demande de contact')
            ->text('Vous vous êtes connecté');
        $this->mailer->send($mail);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
            InteractiveLoginEvent::class => 'onLogin',
        ];
    }
}
