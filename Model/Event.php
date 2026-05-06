<?php
class Event
{
    // Identificador
    public $id;

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

    // Auditoría
    public $createdAt;
    public $updatedAt;

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
        $organizerId = 0,
        $id = null,
        $createdAt = null,
        $updatedAt = null
    ) {
        $this->id = $id;
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
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromDbRow(array $row): self
    {
        return new self(
            $row['titulo'] ?? '',
            $row['descripcion'] ?? '',
            $row['categoria'] ?? '',
            $row['fecha'] ?? '',
            $row['hora'] ?? '',
            $row['direccion'] ?? '',
            $row['codigo_postal'] ?? '',
            $row['ciudad'] ?? '',
            $row['email'] ?? '',
            $row['imagen_portada'] ?? null,
            $row['imagen_ubicacion'] ?? null,
            isset($row['organizador_id']) ? (int) $row['organizador_id'] : 0,
            isset($row['id']) ? (int) $row['id'] : null,
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'titulo'           => $this->title,
            'descripcion'      => $this->description,
            'categoria'        => $this->category,
            'fecha'            => $this->eventDate,
            'hora'             => $this->eventTime,
            'direccion'        => $this->location,
            'codigo_postal'    => $this->postalCode,
            'ciudad'           => $this->city,
            'email'            => $this->email,
            'imagen_portada'   => $this->coverImage,
            'imagen_ubicacion' => $this->locationImage,
            'organizador_id'   => $this->organizerId,
            'created_at'       => $this->createdAt,
            'updated_at'       => $this->updatedAt,
        ];
    }
}
