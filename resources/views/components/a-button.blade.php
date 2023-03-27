@props(['title'=>'voeg juiste titel toe', 'href'=>'#'])

<a class="btn btn-primary mx-1 my-2 rounded-pill" href="{{ route($href)}}">{{$title}}</a>
