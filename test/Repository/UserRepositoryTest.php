<?php 

namespace Razan\belajar\php\mvc\Repository;

use PHPUnit\Framework\TestCase;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSucces()
    {
        $user = new User();
        $user->id = 'razan';
        $user->name = 'Razan';
        $user->password = 'rahasia';

        $this->userRepository->save($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdFailed()
    {
        $user = $this->userRepository->findById('notFound');
        self::assertNull($user);
    }
}