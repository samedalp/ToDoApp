<?php


namespace App\Http\Services;



use App\Repository\DeveloperRepositoryInterface;
use App\Repository\ProviderRepositoryInterface;
use App\Repository\TaskRepositoryInterface;

class ManagementService
{
    protected $providerRepository;
    protected $taskRepository;
    protected $developerRepository;

    public function __construct(ProviderRepositoryInterface $providerRepository, TaskRepositoryInterface $taskRepository, DeveloperRepositoryInterface $developerRepository)
    {
        $this->providerRepository = $providerRepository;
        $this->taskRepository = $taskRepository;
        $this->developerRepository = $developerRepository;
    }

    public function getAllPlan()
    {
        $this->addTaskFromApi();
        $developer = app(DeveloperRepositoryInterface::class)->all()->toArray();
        return app(DeveloperServices::class)->getPlan($developer);
    }

    public function addTaskFromApi()
    {
        $insertData = [];

        $providers = $this->providerRepository->all();
        foreach ($providers as $provider) {
            $parameters = explode(",", $provider->parameters);
            $tasks = app(ApiService::class)->getTasks($provider->endpoint, $parameters);
            foreach ($tasks as $task) {

                $taskCount = $this->taskRepository->getManyByAttributeCounts(["provider" => $provider->id, "name" => $task->name]);
                if ($taskCount == 0) {
                    ["provider" => $provider->id,
                        "name" => $provider->name,
                        "level" => $provider->level,
                        "duration" => $provider->duration,
                    ];
                    array_push($insertData, ["provider" => $provider->id,
                        "name" => $provider->name,
                        "level" => $provider->level,
                        "duration" => $provider->duration,
                    ]);
                }
            }
            $this->taskRepository->addMany($insertData);
        }
    }
}
