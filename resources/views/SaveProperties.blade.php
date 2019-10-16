@extends('master')
@section('title','Saved Properties')
@section('content')

    <div class="container">
        <h4>Saved Propeties list </h4>
        <small><strong> Remaining Properties that can be saved {{$Rcout}} and {{$timeExceed}} </strong></small>
        <ul class="list-group" style="padding-top:10px ">
    @foreach($propertiesList as $item)
                <li class="list-group-item list-group-item-action savedPropertiesList ">
                    <div class="d-flex w-100 justify-content-between">
                        <a href="/getOwnerDetail/{{$item->line1}}/{{$item->line2}}" class="savedPropertyitem"> {{utf8_decode(urldecode($item->line1))}} {{utf8_decode(urldecode($item->line2))}} </a>
                        <a href="/deletesaveproperty/{{$item->id}}" class="btn btn-primary">Delete</a>
                    </div>
                </li>
            @endforeach
    </ul>
    </div>
@endsection

@section('script')
    <script>
        $('.nav-link').removeClass("active");
        $("#menu5").addClass("active");
    </script>
@endsection