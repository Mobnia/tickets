<?php declare(strict_types=1);

namespace App\Controllers\Teams;


use App\Controllers\BaseController;
use App\Models\Team;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest as Request;

/**
 * Class TeamController
 *
 * @package \App\Controllers\Teams
 */
class TeamController extends BaseController
{
    private $team;

    public function __construct(Request $request, Response $response, Team $team)
    {
        $this->team = $team;

        parent::__construct($request, $response);
    }

    public function getTeams()
    {
        $teams = $this->getEverything($this->team);

        foreach ($teams as $team) {
            $this->addTeamDetails($team);
        }

        return $this->convertObjectToArray($teams);
    }

    public function getTeam(ServerRequestInterface $request, $args)
    {
        $id = $args['id'];
        $team = $this->team::find($id);

        $this->addTeamDetails($team);

        return $this->convertObjectToArray($team);
    }

    protected function addTeamDetails($team)
    {
        if(isset($team)) $team->location;
    }
}