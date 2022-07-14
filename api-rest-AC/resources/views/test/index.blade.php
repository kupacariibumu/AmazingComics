<h1>{{$title}}</h1>

<ul>
    @foreach($movies as $movie)
    <li>{{$movie}}</li>
    @endforeach
</ul>