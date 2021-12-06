<?php


namespace App\Http\Services;


use GuzzleHttp\Client;

class ApiService
{
    public function getTasks($endpoint, $parameters)
    {
        $client = new Client();
        $res = $client->request("GET", $endpoint);
        $responseBody = json_decode($res->getBody());

        foreach ($responseBody as $key => $item) {
            if ($parameters[2] == "{key}") {
                foreach ($item as $dataName => $property) {
                    $name = $dataName;
                    $level = $property->{$parameters[0]};
                    $duration = $property->{$parameters[1]};
                }
            } else {
                foreach ($parameters as $parameter) {
                    $param[] = explode(".", $parameter);
                }

                if (count($param[0]) > 1)
                    $level = $item->{$param[0][0]}->{$param[0][1]};
                else
                    $level = $item->{$param[0][0]};

                if (count($param[1]) > 1)
                    $duration = $item->{$param[1][0]}->{$param[1][1]};
                else
                    $duration = $item->{$param[1][0]};

                if (count($param[2]) > 1)
                    $name = $item->{$param[2][0]}->{$param[2][1]};
                else
                    $name = $item->{$param[2][0]};

            }

            $task = new \stdClass();
            $task->name = $name;
            $task->level = $level;
            $task->duration = $duration;
            $newData[] = $task;


        }
        return $newData;
    }
}
