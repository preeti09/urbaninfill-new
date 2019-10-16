@extends('master')
@section('title','Mail Sender')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                @foreach($allTemplates as $template)
                <div class="col">
                    <div class="card" >
                        <div class="card-body">
                            <h5 class="card-title">{{$template->name}}</h5>
                            <p class="card-text">{{$template->description }}</p>
                            <a href="/propertymail/{{$fullname}}/{{$fulladdress}}/{{$emailaddress}}/{{$template->id}}" class="card-link">Use this template</a>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    <div class="card">
        <div class="card-header">
            <h4>Email template</h4>
        </div>
        <div class="card-body">
            <!-- <textarea class="form-control" style="min-width: 100%" name="emailContent"  id="mailContent"  rows="20"></textarea> -->


            <textarea id="content">  {{$data}}</textarea>
        </div>
        <div class="card-footer">

            <div class="input-group mb-3" style="width: 500px; float: right;">

                <input type="email" id="propertyEmailTxt" value="{{$emailaddress}}" class="form-control" placeholder="Enter Email address" aria-label="Email address" aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button  class="btn btn-secondary" id="SendMail" style="float: right; ">Send email</button>
                </div>
            </div>
            <button onclick="printTextArea()" class="btn btn-secondary"  style="float: right;margin-right: 10px" >Print Text</button>
        </div>
    </div>

@endsection

@section('script')
    <script>

        function printTextArea() {
            childWindow = window.open('','childWindow','location=yes, menubar=yes, toolbar=yes');
            childWindow.document.open();
            childWindow.document.write('<html><head></head><body>');
            childWindow.document.write(tinyMCE.activeEditor.getContent());
            childWindow.document.write('</body></html>');
            childWindow.print();
            childWindow.document.close();
            childWindow.close();
        }

        $('#SendMail').click(function () {

            const content =tinyMCE.activeEditor.getContent();
            console.log(content);
            const propertyEmailTxt  = $('#propertyEmailTxt').val();
            console.log(propertyEmailTxt);
            fetch("/propertymail", {
                method: "post", // *GET, POST, PUT, DELETE, etc.
                mode: "cors", // no-cors, cors, *same-origin
                cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                credentials: "same-origin", // include, *same-origin, omit
                body: JSON.stringify({content:content,propertyEmailTxt:propertyEmailTxt}),
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // "Content-Type": "application/x-www-form-urlencoded",
                },
                redirect: "follow", // manual, *follow, error
                referrer: "no-referrer", // no-referrer, *client
            })
                .then(function(response) {
                    if (response.status >= 200 && response.status < 300) {
                        return response.json()
                    }
                    throw new Error(response.statusText)
                }).then(function (data) {
                    console.log(data);
                if(data[0] == "send") {
                    $.notify('Email Send Successfully', 'success');

                }
                else
                {
                    $.notify('Not able to send Email', 'error');

                }
                })

            })

    </script>
@endsection
