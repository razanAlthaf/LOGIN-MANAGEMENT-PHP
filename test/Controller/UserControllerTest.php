<?php
namespace Razan\belajar\php\mvc\App{

    function header(string $value){
        echo $value;
    }
}
namespace Razan\belajar\php\mvc\Controller{
        use PHPUnit\Framework\TestCase;
        use Razan\belajar\php\mvc\Config\Database;
        use Razan\belajar\php\mvc\Repository\UserRepository;
        use Razan\belajar\php\mvc\Domain\User;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userController = new UserController();

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();

        putenv("mode=test");
    }

    public function testRegister()
    {
        $this->userController->register();

        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[Id]');
        $this->expectOutputRegex('[Name]');
        $this->expectOutputRegex('[Password]');
        $this->expectOutputRegex('[Register New User]');
    }

    public function testPostRegisterSuccess()
    {
        $_POST['id'] = 'razan';
        $_POST['name'] = 'Razan';
        $_POST['password'] = 'rahasia';

        $this->userController->postRegister();

        $this->expectOutputRegex('[Location: /users/login]');
    }

    public function testPostRegisterValidationError()
    {
        $_POST['id'] = '';
        $_POST['name'] = 'Razan';
        $_POST['password'] = 'rahasia';

        $this->userController->postRegister();

        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[Id]');
        $this->expectOutputRegex('[Name]');
        $this->expectOutputRegex('[Password]');
        $this->expectOutputRegex('[Register New User]');
        $this->expectOutputRegex("[Id, Name, Password Can't Blank]");
    }

    public function testPostRegisterFailed()
    {
        $user = new User();
        $user->id = 'razan';
        $user->name = 'Razan';
        $user->password = 'rahasia';

        $this->userRepository->save($user);

        $_POST['id'] = 'razan';
        $_POST['name'] = 'Razan';
        $_POST['password'] = 'rahasia';

        $this->userController->postRegister();

        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[Id]');
        $this->expectOutputRegex('[Name]');
        $this->expectOutputRegex('[Password]');
        $this->expectOutputRegex('[Register New User]');
        $this->expectOutputRegex("[Id, Name, Password Can't Blank]");
        $this->expectOutputRegex("[User Id Already Exist]");
    }
    }
}