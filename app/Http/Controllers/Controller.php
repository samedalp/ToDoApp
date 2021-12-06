<?php

namespace App\Http\Controllers;

use App\Http\Services\ManagementService;
use App\Repository\DeveloperRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $service;

    public function __construct(ManagementService $systemService)
    {
        $this->service = $systemService;
    }

    public function index()
    {
        $data["tasks"] = $this->service->getAllPlan();
        return view("tasks", $data);
    }
}
