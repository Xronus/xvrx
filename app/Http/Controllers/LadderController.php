<?php

namespace App\Http\Controllers;

use App\Models\LanguageSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LadderController extends Controller
{
    public function index(Request $request)
    {
        $list = $request->get('list');
        $type = (int) $request->get('type', 5);

        if ($list === 'honorable_kills' || $list === 'time_played') {
            $mode = $list;
        } else {
            $mode = 'arena';
            if (!in_array($type, [2, 3, 5], true)) {
                $type = 5;
            }
        }

        $activeLangs = LanguageSetting::where('is_active', true)->orderBy('sort_order')->get();
        $settings = SiteSetting::first();

        $ladderData = [];
        try {
            if ($mode === 'arena') {
                $ladderData = $this->getArenaLadder($type);
            } elseif ($mode === 'honorable_kills') {
                $ladderData = $this->getHonorableKillsLadder();
            } elseif ($mode === 'time_played') {
                $ladderData = $this->getTimePlayedLadder();
            }
        } catch (\Throwable $e) {
            $ladderData = [];
        }

        return view('ladder.index', compact('ladderData', 'type', 'mode', 'activeLangs', 'settings'));
    }

    /** Арена: ранг, название команды, участники, рейтинг, общая стат. (П-П), стат. недели (П-П), % побед */
    private function getArenaLadder(int $type): array
    {
        $hasWeek = $this->hasColumn('arena_team', 'weekGames');
        $wWeek = $hasWeek ? 'COALESCE(at.weekWins, 0)' : '0';
        $gWeek = $hasWeek ? 'COALESCE(at.weekGames, 0)' : '0';

        $sql = "
            SELECT at.arenaTeamId, at.name AS team_name, at.rating,
                   at.seasonWins AS seasonWins, at.seasonGames AS seasonGames,
                   {$wWeek} AS weekWins, {$gWeek} AS weekGames,
                   GROUP_CONCAT(c.name ORDER BY atm.guid SEPARATOR ' / ') AS members
            FROM arena_team at
            LEFT JOIN arena_team_member atm ON atm.arenaTeamId = at.arenaTeamId
            LEFT JOIN characters c ON c.guid = atm.guid
            WHERE at.type = ?
            GROUP BY at.arenaTeamId
            ORDER BY at.rating DESC
            LIMIT 100
        ";
        $rows = DB::connection('game_char')->select($sql, [$type]);

        $result = [];
        $place = 1;
        foreach ($rows as $row) {
            $seasonWins = (int) ($row->seasonWins ?? 0);
            $seasonGames = (int) ($row->seasonGames ?? 0);
            $seasonLosses = $seasonGames - $seasonWins;
            $weekWins = (int) ($row->weekWins ?? 0);
            $weekGames = (int) ($row->weekGames ?? 0);
            $weekLosses = $weekGames - $weekWins;
            $winPct = $seasonGames > 0 ? round(100 * $seasonWins / $seasonGames, 1) : 0;

            $result[] = [
                'place' => $place++,
                'team_name' => $row->team_name ?? '',
                'members' => $row->members ?? '',
                'rating' => (int) ($row->rating ?? 0),
                'season_wins' => $seasonWins,
                'season_losses' => $seasonLosses,
                'week_wins' => $weekWins,
                'week_losses' => $weekLosses,
                'win_percent' => $winPct,
            ];
        }
        return $result;
    }

    private function getHonorableKillsLadder(): array
    {
        $col = $this->honorKillsColumn();
        if (!$col) {
            return [];
        }
        $sql = "SELECT name, {$col} AS cnt FROM characters WHERE {$col} > 0 ORDER BY {$col} DESC LIMIT 100";
        $rows = DB::connection('game_char')->select($sql);
        $result = [];
        $place = 1;
        foreach ($rows as $row) {
            $result[] = [
                'place' => $place++,
                'name' => $row->name ?? '',
                'count' => (int) ($row->cnt ?? 0),
            ];
        }
        return $result;
    }

    private function getTimePlayedLadder(): array
    {
        if (!$this->hasColumn('characters', 'totaltime')) {
            return [];
        }
        $sql = "SELECT name, totaltime, leveltime FROM characters WHERE totaltime > 0 ORDER BY totaltime DESC LIMIT 100";
        $rows = DB::connection('game_char')->select($sql);
        $result = [];
        $place = 1;
        foreach ($rows as $row) {
            $result[] = [
                'place' => $place++,
                'name' => $row->name ?? '',
                'totaltime' => (int) ($row->totaltime ?? 0),
                'leveltime' => (int) ($row->leveltime ?? 0),
            ];
        }
        return $result;
    }

    private function hasColumn(string $table, string $column): bool
    {
        try {
            $conn = DB::connection('game_char');
            $db = $conn->getDatabaseName();
            $r = $conn->selectOne(
                "SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?",
                [$db, $table, $column]
            );
            return isset($r->c) && (int) $r->c > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function honorKillsColumn(): ?string
    {
        foreach (['totalKills', 'totalHonorPoints'] as $col) {
            if ($this->hasColumn('characters', $col)) {
                return $col;
            }
        }
        return null;
    }

    public static function formatTime(int $seconds): string
    {
        $h = (int) floor($seconds / 3600);
        $m = (int) floor(($seconds % 3600) / 60);
        if ($h >= 24) {
            $d = (int) floor($h / 24);
            $h = $h % 24;
            return "{$d}d {$h}h {$m}m";
        }
        return "{$h}h {$m}m";
    }
}
