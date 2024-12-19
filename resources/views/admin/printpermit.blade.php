<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Details</title>
    <style>
        body {
            padding: 0;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .permit-container {
            padding: 50px;
            font-size: 16px;
        }

        .header_container {
            display: flex;
            flex-direction: row;
            gap: 10px
        }

        .header-text h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header-text h4 {
            margin: 0;
            font-size: 16px;
        }

        .line {
            height: 3px;
            width: 100%;
            background-color: black;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .container_center {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .text_line {
            height: 2px;
            width: 100%;
            background-color: black;
            margin-top: 5px;
        }

        .align_name {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .align_name h4  {
            width: 32%;
            text-align: center;
        }

        .align_label {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .align_label h6 {
            width: 32%;
            text-align: center;
            font-weight: bold;
        }

        .text-column {
            display: flex;
            flex-direction: column
        }

        .text-underline {
            height: 2px;
            width: 100%;
            background-color: black;
            margin-top: 5px;
        }

        .container-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px
        }

        .container-row > div {
            display: flex;
            gap: 10px;
            width: 48%;
        }

        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin-top: 10px;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #000;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }

        .underline {
        text-decoration: underline;
    }

    .cert-container {
        margin-top: 15px
    }

    .radios-container {
        margin-top: 10px;
        display: flex;
        justify-content: center;
        gap: 20px
    }

    .approve-container {
        margin-top: 10px;
    }
    .project-description-container p {
    text-decoration: underline;
}

    .ack-container {
        margin-top: 20px
    }

    p {
        font-size: 15px !important
    }
    </style>
</head>

<body>
    <div class="permit-container">
        <div class="header_container">
            <img src="/img/logo.png" alt="" width="100">
            <div class="header-text">
                <h3>AGL Homeowners Association, Inc.</h3>
                <h5>Brgy. Nancayasan, Urdaneta City, Pangasinan 2428 Philippines</h5>
            </div>
        </div>

        <div class="line"></div>

        <div class="container_center">
            <h4>APPLICATION FOR RENOVATION/EXTENSION/REPAIR PERMIT</h4>
        </div>

        <div>
            <h5>NAME OF APPLICANT</h5>
            <div class="align_name">
                <h4>{{ $applicant->homeowner->lname }}</h4>
                <h4>{{ $applicant->homeowner->fname }}</h4>
                <h4>{{ $applicant->homeowner->mname }}</h4>
            </div>
            <div class="text_line"></div>
            <div class="align_label">
                <h6>Surname</h6>
                <h6>Given Name</h6>
                <h6>Middle Name</h6>
            </div>
        </div>

        <div>
            <h5>AGL HOME ADDRESS</h5>
            <div class="align_name">
                <h4>{{ $applicant->homeowner->lot }}</h4>
                <h4>{{ $applicant->homeowner->block }}</h4>
                <h4>{{ $applicant->homeowner->phase }}</h4>
            </div>
            <div class="text_line"></div>
            <div class="align_label">
                <h6>Lot No.</h6>
                <h6>Block No.</h6>
                <h6>Phase</h6>
            </div>
        </div>

        <div class="container-row">
            <div>
                <h5>Contact Number</h5>
                <div class="text-column">
                    <h4>{{ $applicant->homeowner->phone }}</h4>
                    <div class="text-underline"></div>
                </div>
            </div>
            <div>
                <h5>Target Date of Mobilization</h5>
                <div class="text-column">
                    <h4>{{ $applicant->mobilization_date }}</h4>
                    <div class="text-underline"></div>
                </div>
            </div>
        </div>

        <div class="container-row">
            <div>
                <h5>Date of Application</h5>
                <div class="text-column">
                    <h4>{{ $applicant->application_date }}</h4>
                    <div class="text-underline"></div>
                </div>
            </div>
            <div>
                <h5>Target Date of Completion</h5>
                <div class="text-column">
                    <h4>{{ $applicant->completion_date }}</h4>
                    <div class="text-underline"></div>
                </div>
            </div>
        </div>
            <div>
                <div class="container_center">
                    <h3>BRIEF DESCRIPTION OF PROJECT</h3>
                </div>
                <div class="project-description-container">
                    <p>
                        {{ $applicant->project_description }}
                    </p>
                </div>
            </div>

            <div class="ack-container">
                <h5>NEIGHBOR'S NOTIFICATION & ACKNOWLEDGMENT</h5>

                <!-- Loop through neighbors of a single applicant -->
                @foreach($applicant->neighbors as $neighbor) <!-- Loop through the neighbors -->
                <div class="neighbor-info">
                    <div class="align_name">
                            <h4>{{ $neighbor->homeowner->fname }} {{ $neighbor->homeowner->mname }} {{ $neighbor->homeowner->lname }}</h4>  <!-- Assuming first name field is `fname` -->
                            <h4></h4>  <!-- Assuming last name field is `lname` -->
                            <h4>{{ $neighbor->homeowner->lot }},{{ $neighbor->homeowner->block }}</h4>  <!-- Assuming middle name field is `mname` -->
                    </div>
                    <div class="text_line"></div>
                    <div class="align_label">
                        <h6>Surname</h6>
                        <h6>Signature</h6>
                        <h6>Lot No. Block No.</h6>
                    </div>
                </div>
            @endforeach


            </div>



        <div class="separator">This Line to be Completed by AGL Homeowners Association Administration</div>

        <p class="cert-container">
            This is to certify that <span id="applicant-name" class="underline">Mr/Ms/Sps</span>, a homeowner and a member in good standing of AGL Heights, is hereby granted
            permission to perform the above-mentioned modification of his/her home subject to AGL HOA's Rules and
            Regulations. This permit is valid from <span id="start-date" class="underline">_______________</span> to <span id="end-date" class="underline">_______________</span>.
            THIS IS NOT A CITY PERMIT. You may need to secure any city permit as required by law.
        </p>

        <div class="radios-container">
            <label>
                <input type="radio" name="repairType" value="majorRepair"
                       @if($applicant->selection == 'Major Repair') checked @endif /> Major Repair
            </label>
            <br />
            <label>
                <input type="radio" name="repairType" value="generalRepair"
                       @if($applicant->selection == 'General Repair') checked @endif /> General Repair
            </label>
            <br />
            <label>
                <input type="radio" name="repairType" value="reconstruction"
                       @if($applicant->selection == 'Reconstruction') checked @endif /> Reconstruction
            </label>
        </div>



        <div class="approve-container">
            APPROVED BY <span id="approved-by" class="underline"> {{ $president->homeowner->lname }},
                {{ $president->homeowner->fname }}
                @if($president->homeowner->mname)
                    {{ $president->homeowner->mname }}
                @endif</span> DATE <span id="approval-date" class="underline">______________________</span>
        </div>

        <!-- JavaScript to make it dynamic -->
        <script>
            // Example of dynamically setting values
            document.getElementById("applicant-name").textContent = "John Green";  // Dynamic name
            document.getElementById("start-date").textContent = "01/01/2025";       // Dynamic start date
            document.getElementById("end-date").textContent = "12/31/2025";         // Dynamic end date
            document.getElementById("approved-by").textContent = "Jane Doe";         // Dynamic approver name

        window.onload = function() {
            window.print();
        }
        </script>
</html>


