<?php


namespace App\Http\Services;


use App\Repository\DeveloperRepositoryInterface;
use App\Repository\TaskRepositoryInterface;

class DeveloperServices
{
    protected $developerRepository;

    public function __construct(DeveloperRepositoryInterface $developerRepository)
    {
        $this->developerRepository = $developerRepository;
    }

    public function getPlan(array $developers = [])
    {
        $developerTasks = self::assignTaskToDeveloper($developers);
        foreach ($developerTasks as $key => $developer) {
            $developerTasks[$key]['name']  = $developer['name'];
            $developerTasks[$key]['level'] = $developer['level'];
            $developerTasks[$key]['duration']  = $developer['duration'];
            $developerTasks[$key]['weekly'] = self::weeklyGroup($developer['tasks']);
        }
        return $developerTasks;
    }

    public function assignTaskToDeveloper(array $developerList = [])
    {
        $tasks = app(TaskRepositoryInterface::class)->getManyByAttributes([],"duration","desc");
        $taskGroup = [];
        foreach ($tasks as $task) {
            $taskGroup[$task->level][] = ['id' => $task->id,'name' => $task->name, 'duration' => $task->duration];
        }
        krsort($taskGroup);

        $developers = [];
        foreach ($developerList as $developer) {
            $developers[$developer['level']] = ['name' => $developer['name'], 'level' => $developer['level'], 'duration' => 0];
        }

        foreach ($taskGroup as $level => $tasks) {
            foreach ($tasks as $task) {
                if ( ! isset($developers[$level]))
                    return "backlog";
                $findingDeveloperLevel = self::getDeveloperLevel($developers, $level);
                $developers[$findingDeveloperLevel]['tasks'][] = array_merge($task, ['level' => $level]);
                $developers[$findingDeveloperLevel]['duration']    += $task['duration'];
            }
        }
        return $developers;

    }


    public function getDeveloperLevel(array $developers, int $level): int
    {
        $developer = $developers[$level];
        ksort($developers);

        $index = array_search($level, array_keys($developers));

        $upperLevelDeveloper = array_slice($developers, $index + 1, 1, true);

        if (!isset($upperLevelDeveloper[$level + 1]['duration'])) {
            return $level;
        } elseif ($developer['duration'] <= $upperLevelDeveloper[$level + 1]['duration']) {
            return $level;
        } else {
            $upperLevel = self::getDeveloperLevel($developers, $level + 1);
            if ($upperLevel == $level)
                return $level;
            else
                return $upperLevel;
        }
    }


    private function weeklyGroup(array $tasks = [])
    {
        $weeklyTasks = [
            [
                'tasks' => [],
                'duration'  => 0,
            ],
        ];

        foreach ($tasks as $task) {
            $taskTime = $task['duration'];
            foreach ($weeklyTasks as $key => $week) {
                if ($week['duration'] == 45 && isset($weeklyTasks[$key + 1]))
                    continue;

                if ($week['duration'] == 45 && ! isset($weeklyTasks[$key + 1])) {
                    $task['duration']  = $taskTime;
                    $weeklyTasks[] = [
                        'tasks' => [$task],
                        'duration'  => $task['duration'],
                    ];
                    break;
                }

                if ($week['duration'] < 45 && ($week['duration'] + $taskTime) > 45) {
                    $duration = 45 - $week['duration'];
                    $taskTime -= $duration;
                    $task['duration'] = $duration;
                    $weeklyTasks[$key]['tasks'][] = $task;
                    $weeklyTasks[$key]['duration'] += $duration;
                    $task['duration']  = $taskTime;
                    $weeklyTasks[] = [
                        'tasks' => [$task],
                        'duration'  => $task['duration'],
                    ];
                    break;
                }

                if ($week['duration'] < 45 && ($week['duration'] + $taskTime) <= 45) {
                    $weeklyTasks[$key]['tasks'][] = $task;
                    $weeklyTasks[$key]['duration'] += $taskTime;
                    break;
                }
            }
        }
        return $weeklyTasks;
    }

}
