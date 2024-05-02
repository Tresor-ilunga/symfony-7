<?php

declare(strict_types=1);

namespace App\Form;

use DateTimeImmutable;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class FormListenerFactory
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class FormListenerFactory
{

    public function __construct(private SluggerInterface $slugger)
    {}
    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();
            if (empty($data['slug']))
            {
                $data['slug'] = strtolower((string)$this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event)
        {
            $data = $event->getData();
            $data->setUpdatedAt(new DateTimeImmutable());
            if (!$data->getId())
            {
                $data->setCreatedAt(new DateTimeImmutable());
            }
        };
    }
}