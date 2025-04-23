@extends('partials.layouts.layout')

@section('title', 'Permit Application') <!-- This is fine if you're yielding 'title' in layout -->

@section('content')

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 0.75rem;
        }

        .container {
            border: 2px solid black;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #ccc;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }

        .header h1 {
            margin: 10px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .section-title {
            background-color: #c0c0c0;
            padding: 5px 10px;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #999;
            padding: 8px;
            vertical-align: top;
        }

        .separator {
            border-top: 1px solid #999;
        }

        .checkbox-group {
            margin: 5px 0;
        }

        .official-section {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            border-top: 1px solid #999;
            border-bottom: 1px solid #999;
        }

        .underline {
            border-bottom: 1px solid #999;
            display: inline-block;
            min-width: 200px;
        }

        .header-container {
            display: grid;
            grid-template-columns: 3fr 1fr;
            /* now the text takes more space */
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-picture img {
            max-width: 100px;
            height: auto;
            border-radius: 8px;
            display: block;
            margin-left: auto;
        }

        .content img {
            max-width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .content h2,
        .content h1 {
            margin: 0;
            line-height: 1.3;
        }

        .content h2 {
            font-size: 16px;
            font-weight: normal;
        }

        .content h1 {
            font-size: 20px;
            font-weight: bold;
        }
    </style>


    <div class="container">

        <table>
            <tr>
                <td width = "25%"> <img src="{{ $imageSrc }}" class = "round"alt="No Image found" id="applicant_img" style="width: 10rem;"></td>
                <td width = "75%">
                    <p>The Public Health Act</p>
                    <p>Application for Food Handler's Permit</p>
                </td>
            </tr>
        </table>
        {{-- <div class="header">
            <div class="header-container">
                <div class="content">
                    <img src="{{ public_path('images/coat_of_arms.png') }}" alt="Official Coat of Arms">

                </div>

                <div class="profile-picture">

                </div>
            </div>


        </div> --}}

        <div class="section-title">PERSONAL INFORMATION</div>
        <table>
            <tr>
                <td>Firstname: {{ $permit_application->firstname }}</td>
                <td>Middlename:</td>
                <td>Lastname: {{ $permit_application->lastname }}</td>
            </tr>
            <tr>
                <td>Address: {{ $permit_application->address }}</td>
                <td>Street:{{ $permit_application->address }}</td>
                <td>Parish: {{ $permit_application->address }}</td>
            </tr>
            <tr>
                <td>
                    Date of Birth:{{ $permit_application->date_of_birth }}
                </td>
                <td>
                    Telephone:{{ $permit_application->cell_phone }}
                </td>
                <td>
                    Telephone:{{ $permit_application->home_phone }}
                </td>
                <td>
                    Telephone:{{ $permit_application->work_phone }}
                </td>
                <td>
                    TRN:{{ $permit_application->trn ?? 'NULL' }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td>
                    ID TYPE:
                </td>
                <td>
                    ID NUMBER:
                    
                </td>
                <td>GENDER: {{ strtoupper($permit_application->gender) }}</td>
            </tr>
        </table>

        <div class="section-title">EMPLOYEMENT/EDUCATION INFORMATION</div>
        <table>
            <tr>
                <td>
                    ARE YOU CURRENTLY EMPLOYED:
                    YES:______ NO______
                </td>
                <td>
                    ARE YOU CURRENTLY ENROLLED AS A STUDENT?
                    YES______ NO______
                </td>
            </tr>
            <tr>
                <td>
                   Occupation: {{ $permit_application->occupation }}
                </td>
                <td>
                    NAME OF EMPLOYER:<br>
                    {{ $permit_application->employer }}
                </td>
            </tr>
            <tr>
                <td>
                    ADDRESS OF EMPLOYER: {{ $permit_application->employer_address }}
                </td>
            </tr>
            <tr>
                <td>Address:</td>
                <td>Street:</td>
                <td>Parish:</td>
            </tr>
            <tr>
                <td>
                    Have you ever applied for a Food Handler's Permit? {{ strtoupper($permit_application->applied_before = 0 ? 'Yes' : 'No') }}
                </td>
                <td>
                    Was the application refused? {{ $permit_application->granted = null ? 'Yes' : 'No' }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td colspan="2">
                    If YES, please state the reason:_________________________________________________
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Number on most recent Food Handlers' Permit: _________________________________
                </td>
            </tr>
            <tr>
                <td width="50%">
                    Date of Application:{{ $permit_application->application_date }}
                </td>
                <td width="50%">
                    Signature of Applicant:__________________
                </td>
            </tr>
        </table>

        <div class="official-section">FOR OFFICIAL USE ONLY</div>

        <table>
            <tr>
                <td>Fee Amount Paid:</td>
                <td>Receipt Number:</td>
                <td>Application: {{ $permit_application->id }}</td>
                <td>Permit: {{ $permit_application->permit_no }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    Date of Examination:
                </td>
            </tr>
            <tr>
                <td colspan="2">Granted:</td>
                <td colspan="2">Refused:</td>
            </tr>
            <tr>
                <td colspan="4">
                    Reason for Refusal:
                </td>
            </tr>
            <tr>
                <td>Date:</td>
                <td colspan="3">MOH Signature:</td>
            </tr>
        </table>
    </div>
@endsection
