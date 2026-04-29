<?php
class Event
{
    // Información básica
    public $title;
    public $description;
    public $category;

    // Fecha y hora
    public $eventDate;
    public $eventTime;

    // Ubicación
    public $location;
    public $postalCode;
    public $city;

    // Contacto
    public $email;

    // Imágenes
    public $coverImage;
    public $locationImage;

    // Organizador
    public $organizerId;

    public function __construct(
        $title = "",
        $description = "",
        $category = "",
        $eventDate = "",
        $eventTime = "",
        $location = "",
        $postalCode = "",
        $city = "",
        $email = "",
        $coverImage = null,
        $locationImage = null,
        $organizerId = 0
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->eventDate = $eventDate;
        $this->eventTime = $eventTime;
        $this->location = $location;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->email = $email;
        $this->coverImage = $coverImage;
        $this->locationImage = $locationImage;
        $this->organizerId = $organizerId;
    }
}
