@extends('master')
@section('title','Person Search')
@section('content')

    <div class="pt-3">
        <div class="row">
            <div class="col">

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-text text-muted" for="fName">Enter First Name</label>
                        <input type="text" id="fName" class="form-control" required placeholder="First name">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-text text-muted" for="lName">Enter Last Name</label>
                        <input type="text" id="lName" class="form-control" required placeholder="Last name">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-text text-muted" for="zip">Enter Zip Code</label>
                            <input type="text" id="zip" class="form-control" required placeholder="zip">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-text text-muted"> &nbsp </label>
                            <button type="button" class="btn btn-primary" id="searchbyPerson">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
        <div class="list-group" id="personSearchListdiv">

        </div>
        </div>

    </div>


@endsection

@section('script')
<script>
    $('.nav-link').removeClass("active");
    $("#menu4").addClass("active");
</script>
@endsection