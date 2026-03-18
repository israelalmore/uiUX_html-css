<?php
class User
{
    // Información personal
    public $name;
    public $surname1;
    public $surname2;
    public $birthdate;

    // Tipo de usuario
    public $userType;

    // Contacto
    public $email;
    public $telephone;
    public $language;

    // Identificación
    public $documentType;
    public $document;

    // Dirección
    public $city;
    public $postalCode;
    public $province;

    // Seguridad
    public $password;

    public function __construct(
        $name = "", //1
        $surname1 = "",//2
        $surname2 = "",
        $birthdate = "",
        $userType = "",
        $email = "",
        $telephone = "",
        $language = "",
        $documentType = "",
        $document = "",
        $city = "",
        $postalCode = "",
        $province = "",
        $password = ""
    ) {
        $this->name = $name;
        $this->surname1 = $surname1;
        $this->surname2 = $surname2;
        $this->birthdate = $birthdate;
        $this->userType = $userType;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->language = $language;
        $this->documentType = $documentType;
        $this->document = $document;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->province = $province;
        $this->password = $password;
    }
}
