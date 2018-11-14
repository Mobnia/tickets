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

    public function __construct(Request $request, Team $team)
    {
        $this->team = $team;

        parent::__construct($request);
    }

    public function getTeams()
    {
        $teams = $this->getAllRecords($this->team);

        foreach ($teams as $team) {
            $this->addTeamDetails($team);
        }

        return $this->convertObjectToArray($teams);
    }

    public function getTeam(ServerRequestInterface $request, $args)
    {
        $teamId = $args['id'];
        $team = $this->team::find($teamId);

        if(isset($team)) $this->addTeamDetails($team);

        return $this->convertObjectToArray($team);
    }

    protected function addTeamDetails($team)
    {
        if(isset($team)) $team->location;
    }
}