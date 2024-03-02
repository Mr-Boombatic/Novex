<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    private function fillUser(User &$user, array $userData)
    {
        foreach ($userData as $key => $value) {
            switch ($key) {
                case 'email':
                    $user->setEmail($value);
                    break;
                case 'phone':
                    $user->setPhone($value);
                    break;
                case 'name':
                    $user->setName($value);
                    break;
                case 'age':
                    $user->setAge($value);
                    break;
                case 'sex':
                    $user->setSex($value);
                    break;
                case 'birthday':
                    $value = new \DateTime($value);
                    $user->setBirthday($value);
                    break;
            }
        }
    }

    public function saveUser(array $userData)
    {
        $user = new User();
        $this->fillUser($user, $userData);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return (string) $errors;
        } else {

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function updateUser(array $userData)
    {
        $user = null;
        if (array_key_exists('id', $userData) && $userData['id'] !== null) {
            $user = $this->entityManager->getRepository(User::class)->find($userData['id']);
        } else {
            return "Id parametr isn't specified";
        }

        if ($user == null)
            return sprintf('User with specified id (%s) not found', $userData['id']);

        $this->fillUser($user, $userData);
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            return (string) $errors;
            
        } else {
            $this->entityManager->flush();
        }
    }
}
