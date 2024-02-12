@extends('layouts.app')

@section('content')
<div class="row row-cols-1 row-cols-md-3 g-4">
    <!-- Snake -->
    <div class="col text-center">
        <table class="table table-bordered table-striped caption-top">
            <caption class="fs-5"><b>Top 10 Snake players</b></caption>
            <thead>
                <tr style="white-space: nowrap;">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Score</th>
                    <th scope="col">Games played</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach($topSnake as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['top_score'] }}</td>
                        <td>{{ $item['num_of_games'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Tetris -->
    <div class="col text-center">
        <table class="table table-bordered table-striped caption-top">
            <caption class="fs-5"><b>Top 10 Tetris players</b></caption>
            <thead>
                <tr style="white-space: nowrap;">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Score</th>
                    <th scope="col">Games played</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach($topTetris as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['top_score'] }}</td>
                        <td>{{ $item['num_of_games'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- SeaBattle -->
    <div class="col text-center">
        <table class="table table-bordered table-striped caption-top">
            <caption class="fs-5"><b>Top 10 Sea battle players</b></caption>
            <thead>
                <tr style="white-space: nowrap;">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Victories</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach($topSeaBattle as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['score'] }}</td>
                        <td>{{ $item['num_of_wins'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('css')
<!--  -->
@endpush