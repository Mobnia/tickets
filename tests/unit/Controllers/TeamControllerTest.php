<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: ifenna
 * Date: 11/9/18
 * Time: 3:38 PM
 */

namespace App\Test\unit\Controllers;


use App\Controllers\Teams\TeamController;
use App\Models\Team;

class TeamControllerTest extends BaseController
{
    private $team;
    private $teamController;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName); // request and response are constructed in the parent

        $this->team = new Team();
        $this->teamController = new TeamController($this->request, $this->response, $this->team);
    }

    public function testGetTeams()
    {
        $this->assertArrayHasKey('data', $this->teamController->getTeams());
    }

    public function testGetTeam()
    {
        $this->assertArrayHasKey('data', $this->teamController->getTeam($this->request, ['id' => 1]));
    }
}
