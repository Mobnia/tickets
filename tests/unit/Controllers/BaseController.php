<?php declare(strict_types=1);

namespace App\Test\unit\Controllers;


use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase;

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
        $this->request = $this->createMock('Zend\Diactoros\ServerRequest');
        $this->response = $this->createMock('Zend\Diactoros\Response');

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
        $dbName = getenv("DB_NAME");
        $dbPassword = getenv("DB_PASSWORD");

        $db = new Manager();
        $db->addConnection([
            'driver' => "mysql",
            'host' => $dbHost,
            'database' => $dbName,
            'username' => $dbUser,
            'password' => $dbPassword,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();
    }
}