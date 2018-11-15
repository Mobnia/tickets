<?php declare(strict_types=1);

namespace App\Controllers\Teams;


use App\Controllers\BaseController;
use App\Models\Team;
use Aura\Filter\ValueFilter;
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

    public function __construct(ValueFilter $filter, Team $team)
    {
        $this->team = $team;

        parent::__construct($filter);
    }

    public function getTeams()
    {
        $teams = $this->getAllRecords($this->team);

        foreach ($teams as $team) {
            $this->addTeamDetails($team);
        }

        return $this->returnResponse($teams);
    }

    public function getTeam(Request $request, $args)
    {
        $teamId = $args['id'];
        $team = $this->team::find($teamId);

        if(isset($team)) $this->addTeamDetails($team);

        return $this->returnResponse($team);
    }

    protected function addTeamDetails($team)
    {
        $team->location;
    }
}