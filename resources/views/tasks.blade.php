<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To Do App</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="#">To Do App</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
</nav>
<div class="container-fluid bg-light">
    <div class="row">
        <div class="col-sm-12">
            @foreach($tasks as $developer)
                <div class="bg-secondary  my-3 text-light text-center">
                    <h4>{{ $developer['name'] }}</h4>
                    Level : {{ $developer['level'] }}x - Total Duration : {{ $developer['duration'] }}h
                </div>
                <div class="row">
                    @foreach($developer['weekly'] as $week => $daily)
                        <div class="col-sm">
                            <h5 class="text-dark">{{ ($week + 1) }}. Week</h5>
                            @foreach($daily['tasks'] as $task)

                                <div class="card col-md-12 my-3 p-2 bg-light">
                                    <h5 class="card-header p-0 m-0"> {{ $task['name'] }}</h5>
                                    <div class="card-body p-0">
                                        <hr class="m-1">
                                        <div>
                                            <span class="col-sm-6 p-0 m-0">Level: {{ $task['level'] }}x</span> -
                                            <span class="col-sm-6 p-0 m-0">Duration: {{ $task['duration'] }}h</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <hr class="py-5">

            @endforeach

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
