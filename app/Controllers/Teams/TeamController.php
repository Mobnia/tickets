<?php declare(strict_types=1);

namespace App\Controllers\Teams;


use App\Controllers\BaseController;
use App\Models\Team;
use Aura\Filter\ValueFilter;
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

    public function getTeams(Request $request)
    {
        $teams = $this->getAllRecords($this->team);

        $page = $this->getPage($request);

        foreach ($teams as $team) {
            $this->addTeamDetails($team);
        }

        return $this->returnResponse($teams, $page);
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