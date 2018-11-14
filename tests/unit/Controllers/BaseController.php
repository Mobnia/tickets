<?php declare(strict_types=1);

namespace App\Test\unit\Controllers;


use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * Class BaseController
 *
 * @package \App\Test\unit\Controllers
 */
class BaseController extends TestCase
{
    protected $request;
    protected $response;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        // TODO: Stop mocking what you don't own
        $this->request = new ServerRequest();

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp()
    {
        $this->configureDatabase();
    }

    private function configureDatabase()
    {
        $dbUser = getenv("DB_USER");
        $dbHost = getenv("DB_HOST");
        $dbPassword = getenv("DB_PASSWORD");

        $manager = new Manager();
        $manager->addConnection([
            'driver' => "mysql",
            'host' => $dbHost,
            'database' => 'test_dms_sample',
            'username' => $dbUser,
            'password' => $dbPassword,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $manager->bootEloquent();
        $manager->setAsGlobal();
    }
}