@extends('master')
@section('title','Saved Properties')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Detail information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- First Col  -->
                <div class="col-5" style="padding-left: 0px;padding-right: 0px">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Names list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Full Name
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="NameTableBody">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Email list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Email address
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="EmailTableBody">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    RelationShip list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                First Name
                                            </th>
                                            <th>
                                                Last Name
                                            </th>
                                            <th>
                                                Type
                                            </th>
                                            <th>
                                                Phone Number Lists
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="RelationTableBody">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Bankruptcy list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>
                                                    Law Firm
                                                </th>
                                                <th>
                                                    Attorney Number
                                                </th>
                                                <th>
                                                    Case Status
                                                </th>
                                                <th>
                                                    Case Status Date
                                                </th>
                                                <th>
                                                    Date Collected
                                                </th>
                                                <th>
                                                    Filing Date
                                                </th>
                                                <th>
                                                    Full Case Number
                                                </th>
                                                <th>
                                                    Judge Name
                                                </th>
                                                <th>
                                                    Screen
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="BankruptcyTableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Criminal list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>
                                                    Case Number
                                                </th>
                                                <th>
                                                    case Type
                                                </th>
                                                <th>
                                                    Charges Filled Date
                                                </th>
                                                <th>
                                                    Code
                                                </th>
                                                <th>
                                                    Court
                                                </th>
                                                <th>
                                                    Description
                                                </th>
                                                <th>
                                                    Disposition
                                                </th>
                                                <th>
                                                    dlNumber
                                                </th>
                                                <th>
                                                    Fine
                                                </th>
                                                <th>
                                                    Source Name
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="CriminalTableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second Column -->
                <div class="col-7" style="padding-left: 0px;padding-right: 0px">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    DOB list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Date
                                            </th>
                                            <th>
                                                Age
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="dobTableBody">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Phone Number list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Type
                                            </th>
                                            <th>
                                                Number
                                            </th>
                                            <th>
                                                Provider
                                            </th>
                                            <th>
                                                Business
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody id="PhoneTableBody">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Property list
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Property Address
                                            </th>
                                            <th>
                                                Assessed Value
                                            </th>
                                            <th>
                                                Assessor Year
                                            </th>
                                            <th>
                                                Date
                                            </th>
                                            <th>
                                                Improvement Value
                                            </th>
                                            <th>
                                                Land Value
                                            </th>
                                            <th>
                                                Market Value
                                            </th>
                                            <th>
                                                Tax Year
                                            </th>
                                            <th>
                                                Total Tax
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody id="PropertyTableBody">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>









@endsection

@section('script')
    <script>
        const personDetail = {!! json_encode($persondetail) !!};
        const nameList = personDetail.name.map((names)=>{
            return "<tr>"+"<td>"+names.data+"</td>"+"</tr>";
        });
        const dobList = personDetail.dob.map((item)=>{
            return "<tr>"+"<td>"+item.date.data+"</td>"+"<td>"+item.age+"</td>"+"</tr>";
        });
        const phoneList = personDetail.phone.map((item)=>{
            return "<tr>"+"<td>"+item.type+"</td>"+"<td>"+item.number+"</td>"+"<td>"+item.providerName+"</td>"+"<td>"+item.business+"</td>"+"</tr>";
        });
        const EmailList = personDetail.email.map((item)=>{
            return "<tr>"+"<td>"+item.data+"</td>"+"</tr>";
        });
        const relationList = personDetail.relationship.map((item)=>{
            return "<tr>"+"<td>"+item.name.first+"</td>"+"<td>"+item.name.last+"</td>"+"<td>"+item.type+"</td>"+ "<td> <ul>"+
                item.phone.map((num)=>{return "<li>"+num.number+"</li>"}) +
                "<ul> </td>"+ "</tr>";
        });
        const propertyList = personDetail.property.map((item)=>{
            return "<tr>"+"<td>"+item.address.data+"</td>"+"<td>"+item.assessment.assessedValue+"</td>"+"<td>"+item.assessment.assessorYear+"</td>"+"<td>"+item.assessment.date.data+"</td>"+
                "<td>$"+item.assessment.improvementValue+"</td>"+"<td>$"+item.assessment.landValue+"</td>"+"<td>$"+item.assessment.marketValue+"</td>"+"<td>"+item.assessment.taxYear+"</td>"+
                "<td>$"+item.assessment.totalTax+"</td>"+
                "</tr>";
        });

        const bankruptcyList = personDetail.bankruptcy.map((item)=>{
            return "<tr>"+"<td>"+item.attorney.lawFirm+"</td>"+"<td>"+item.attorney.phone+"</td>"+"<td>"+item.caseStatus+"</td>"+"<td>"+item.caseStatusDate.data+"</td>"+"<td>"+item.dateCollected.data+"</td>"
                +"<td>"+item.filingDate.data+"</td>"+"<td>"+item.fullCaseNumber+"</td>"+"<td>"+item.judgeName+"</td>"+"<td>"+item.screen+"</td>"
                +"</tr>";
        });
        let criminalList = [];
        personDetail.criminal.map((items)=> {
                items.offense.map((item) => {
                        const a = "<tr>" + "<td>" + item.caseNumber + "</td>" + "<td>" + item.caseType + "</td>" + "<td>" + item.chargesFiledDate.data + "</td>" + "<td>" + item.code + "</td>" + "<td>" + item.court + "</td>"
                            + "<td>" + item.description + "</td>" + "<td>" + item.disposition.data + "</td>" + "<td>" + item.dlNumber + "</td>" + "<td>" + item.fines + "</td>" + "<td>" + item.sourceName + "</td>" + "<td>" + item.status + "</td>"
                            + "</tr>";
                        criminalList.push(a);
                        return a;
                    }
                )
            }
        );
        $('#NameTableBody').append(nameList);
        $('#dobTableBody').append(dobList);
        $('#PhoneTableBody').append(phoneList);
        $('#EmailTableBody').append(EmailList);
        $('#RelationTableBody').append(relationList);
        $('#PropertyTableBody').append(propertyList);
        $('#BankruptcyTableBody').append(bankruptcyList);
        $('#CriminalTableBody').append(criminalList);
        console.log( personDetail );

    </script>
@endsection