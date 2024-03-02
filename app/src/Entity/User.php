<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
class User
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["default", "create", "update"])]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\p{Lu}\p{Ll}+$/u',
        message: 'Имя пользователя должно начинаться с заглавной буквы и содержать только буквенные символы.'
    )]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Неправильный формат адреса электронной почты.'
    )]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\GreaterThan(value: 0, message: 'Значение должно быть целым числом больше 0.')]
    private ?int $age = null;

    #[Assert\NotBlank]
    #[Assert\Choice(
        choices: ['male', 'female', 'transformer', 'Ryan Gosling'],
        message: 'Допустимые значения для поля sex: "man", "woman", "transformer" и "Ryan Gosling".'
    )]
    private ?string $sex = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',
        message: 'Неправильный формат номера телефона.'
    )]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
