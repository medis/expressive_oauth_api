<?php

namespace AuthTest;

use Auth\Handler\UserLoginHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Tuupola\Http\Factory\ResponseFactory;
use Tuupola\Http\Factory\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Authentication\Session\PhpSession;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Authentication\UserRepositoryInterface;
use Zend\Expressive\Authentication\Session\ConfigProvider;
use Zend\Expressive\Authentication\DefaultUser;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

// Using requests https://github.com/tuupola/slim-basic-auth/blob/3.x/tests/BasicAuthenticationTest.php

class AuthTest extends TestCase
{
    /** @var ServerRequestInterface|ObjectProphecy */
    private $request;

    /** @var PhpSession|ObjectProphecy */
    private $phpSession;

    public function setUp()
    {
        $this->request = $this->prophesize(ServerRequestInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->authenticatedUser = $this->prophesize(UserInterface::class);
        $this->defaultConfig = (new ConfigProvider())()['authentication'];
        $this->session = $this->prophesize(SessionInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->urlHelper = $this->prophesize(UrlHelper::class);
        $this->userFactory  = function (string $identity, array $roles = [], array $details = []) : UserInterface {
            return new DefaultUser($identity, $roles, $details);
        };
        $this->response = function () {
            return $this->responsePrototype->reveal();
        };
    }

    public function testUserCanLogin()
    {

        $this->session
            ->has(UserInterface::class)
            ->willReturn(false);
        $this->session
            ->set(UserInterface::class, [
                'username' => '11',
                'roles'    => ['captain'],
                'details'  => ['gender' => 'male'],
            ])
            ->shouldBeCalled();

        $this->session
            ->regenerate()
            ->shouldBeCalled();

        $this->session
            ->has(UserInterface::class)
            ->willReturn(false);

        $this->request
            ->getAttribute('session')
            ->willReturn($this->session->reveal());

        $this->request
            ->getMethod()
            ->willReturn('POST');

        $this->request
            ->getParsedBody()
            ->willReturn([
                'username' => '11',
                'password' => '22'
            ]);

        $this->authenticatedUser
            ->getIdentity()
            ->willReturn('11');

        $this->authenticatedUser
            ->getRoles()
            ->willReturn(['captain']);

        $this->authenticatedUser
            ->getDetails()
            ->willReturn(['gender' => 'male']);

        $this->userRepository
            ->authenticate('11', '22')
            ->willReturn($this->authenticatedUser->reveal());

        $phpSession = new PhpSession(
            $this->userRepository->reveal(),
            $this->defaultConfig,
            $this->response,
            $this->userFactory
        );

        $user = $phpSession->authenticate($this->request->reveal());

        $this->assertSame($this->authenticatedUser->reveal(), $user);
    }

    public function testUserCannotLoginWithIncorrectDetails()
    {
        $this->session
            ->has(UserInterface::class)
            ->willReturn(false);

        $this->request
            ->getAttribute('session')
            ->willReturn($this->session->reveal());

        $this->request
            ->getMethod()
            ->willReturn('POST');

        $this->request
            ->getParsedBody()
            ->willReturn([
                'username' => '11',
                'password' => '22'
            ]);

        $this->userRepository
            ->authenticate('11', '22')
            ->willReturn(null);

        $phpSession = new PhpSession(
            $this->userRepository->reveal(),
            $this->defaultConfig,
            $this->response,
            $this->userFactory
        );

        $this->assertNull($phpSession->authenticate($this->request->reveal()));
    }

    public function testGetRequestReturnsLoginForm()
    {
        $renderer = $this->prophesize(TemplateRendererInterface::class);
        $renderer
            ->render('app::login', Argument::type('array'))
            ->willReturn('');

        $phpSession = new PhpSession(
            $this->userRepository->reveal(),
            $this->defaultConfig,
            $this->response,
            $this->userFactory
        );

        $this->urlHelper
            ->generate('login')
            ->willReturn('/login');

        $login = new UserLoginHandler(
            $renderer->reveal(),
            $phpSession,
            $this->urlHelper->reveal()
        );

        $this->request
            ->getAttribute('session')
            ->willReturn($this->session->reveal());

        $this->request
            ->getHeaderLine('Referer')
            ->willReturn('/');

        $this->request
            ->getMethod()
            ->willReturn('GET');

        $response = $login->handle(
            $this->request->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}