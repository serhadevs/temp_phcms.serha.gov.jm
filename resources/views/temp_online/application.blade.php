<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>PHCMS - Online Application</title>
    <base href="/">
    <link href="./tabler/dist/css/tabler.min.css?1692870487" rel="stylesheet" />
    {{-- <link href="./tabler/dist/css/tabler-flags.min.css?1692870487" rel="stylesheet" /> --}}
    {{-- <link href="./tabler/dist/css/tabler-payments.min.css?1692870487" rel="stylesheet" /> --}}
    {{-- <link href="./tabler/dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet" /> --}}
    {{-- <link href="./tabler/dist/css/demo.min.css?1692870487" rel="stylesheet" /> --}}
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        #signature-pad {
            cursor: pointer;
        }
    </style>

    {{-- Signature --}}
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
        rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
    <style>
        .kbw-signature {
            width: 50%;
            height: 400px;
        }

        #sig canvas {
            width: 50% !important;
            height: 50%;
        }
    </style>
</head>

<body class=" d-flex flex-column">
    <script src="./tabler/dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
        <div class="container container-narrow py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="./images/serha_logo.png" class="w-7">
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body" id="card-1" style="">
                    <h2 class="h2 text-center mb-4">Enter Personal Information</h2>
                    <div class="mb-3">
                        <label class="form-label" id="fname_label">First Name</label>
                        <input type="text" class="form-control" placeholder="John" name="fname" autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" id="mname_label">
                            Middle Name
                        </label>
                        <input type="text" class="form-control" placeholder="Michelle" name="mname"
                            autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="lname_label">Last Name</label>
                        <input type="text" class="form-control" placeholder="Brown" name="lname">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="dob_label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="sex_label">Gender</label>
                        <select name="sex" id="" class="form-select">
                            <option value="">Please select a gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-footer">
                        <button type="button" class="btn btn-primary w-100" onclick="validatePersonal('individual')">
                            Next Section</button>
                    </div>
                </div>
                <div class="card-body" id="card-2" style="display:none">
                    <h2 class="h2 text-center mb-4">Enter Contact Information</h2>
                    <div class="mb-3">
                        <label class="form-label" id="mail_address_label">Email Address</label>
                        <input type="email" class="form-control" placeholder="john.doe@gmail.com" autocomplete="off"
                            name="mail_address">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" id="cell_num_label">
                            Cellphone Number
                        </label>
                        <input type="text" class="form-control" placeholder="+1(XXX)XXX-XXXX" autocomplete="off"
                            name="cell_num">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="home_num_label">
                            Home Phone Number
                        </label>
                        <input type="text" class="form-control" name="home_num" placeholder="+1(XXX)XXX-XXXX">
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 mb-2">
                            <label for="" class="form-label" id="address_label">Street Address</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                        <div class="col-xs-12 col-sm-12 mb-2">
                            <label for="" class="form-label" id="parish_label">Parish</label>
                            <select name="parish" id="" class="form-control">
                                <option value="">Please select a Parish</option>
                                <option value="Kingston">Kingston</option>
                                <option value="St.Andrew">St.Andrew</option>
                                <option value="St.Catherine">St.Catherine</option>
                                <option value="St.Thomas">St.Thomas</option>
                                <option value="Clarendon">Clarendon</option>
                                <option value="St.Elizabeth">St.Elizabeth</option>
                                <option value="Manchester">Manchester</option>
                                <option value="Westmoreland">Westmoreland</option>
                                <option value="Hanover">Hanover</option>
                                <option value="St.Ann">St.Ann</option>
                                <option value="St.Mary">St.Mary</option>
                                <option value="St.James">St.James</option>
                                <option value="Trelawny">Trelawny</option>
                                <option value="Portland">Portland</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-footer">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-secondary w-100" type="button"
                                    onclick="traverse(1, 2)">Previous
                                    Section</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-primary w-100" type="button"
                                    onclick="validateContact('individual')">Next
                                    Section</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="card-3" style="display:none">
                    <h2 class="h2 text-center mb-4">Enter Employment Information</h2>
                    <div class="mb-3">
                        <label class="form-label" id="trn_label">Tax Registration Number</label>
                        <input type="text" class="form-control" placeholder="XXX-XXX-XXX" name="trn"
                            autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" id="occupation_label">
                            Occupation
                        </label>
                        <input type="occupation" class="form-control" placeholder="eg. Teacher" name="occupation">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="employer_label">Name of Employer</label>
                        <input type="text" class="form-control" name="employer">
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="employer_address_label">Street Address of
                                Employer</label>
                            <input type="text" class="form-control" name="employer_address">
                        </div>
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="employer_parish_label">Parish of
                                Employer</label>
                            <select name="employer_parish" id="" class="form-control">
                                <option value="">Please select a Parish</option>
                                <option value="Kingston">Kingston</option>
                                <option value="St.Andrew">St.Andrew</option>
                                <option value="St.Catherine">St.Catherine</option>
                                <option value="St.Thomas">St.Thomas</option>
                                <option value="Clarendon">Clarendon</option>
                                <option value="St.Elizabeth">St.Elizabeth</option>
                                <option value="Manchester">Manchester</option>
                                <option value="Westmoreland">Westmoreland</option>
                                <option value="Hanover">Hanover</option>
                                <option value="St.Ann">St.Ann</option>
                                <option value="St.Mary">St.Mary</option>
                                <option value="St.James">St.James</option>
                                <option value="Trelawny">Trelawny</option>
                                <option value="Portland">Portland</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="work_num_label">Work Number</label>
                        <input type="text" class="form-control" name="work_num" placeholder="+1(XXX)XXX-XXXX">
                    </div>
                    <div class="form-footer">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary w-100"
                                    onclick="traverse(2, 3)">Previous Section</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary w-100"
                                    onclick="validateEmployment('individual')">Next
                                    Section</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="card-4" style="display:none">
                    <h2 class="h2 text-center mb-4">Enter Additional Information</h2>
                    <div class="mb-3">
                        <label class="form-label" id="teacher_label">Are you a teacher</label>
                        <select name="teacher" id="" class="form-select">
                            <option value="">Please enter an answer</option>
                            <option value="">Yes</option>
                            <option value="">No</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" id="student_label">
                            Are you a student?
                        </label>
                        <select name="student" id="" class="form-select">
                            <option value="">Please enter an answer</option>
                            <option value="">Yes</option>
                            <option value="">No</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label" id="applied_label">Have you ever applied for a Food
                            Handler's
                            Permit?</label>
                        <select name="applied" id="" class="form-select">
                            <option value="">Please enter an answer</option>
                            <option value="">Yes</option>
                            <option value="">No</option>
                        </select>
                    </div>
                    <div class="mb-2" style="">
                        <label for="" class="form-label" id="years_label">How many years until you graduate
                            your current
                            school?</label>
                        <input type="text" class="form-control" name="years">
                    </div>
                    <div class="mb-2" style="">
                        <label for="" class="form-label" id="granted_label">Did you receive your food
                            handler's permit?</label>
                        <select name="granted" id="" class="form-select">
                            <option value="">Please enter an answer</option>
                            <option value="">Yes</option>
                            <option value="">No</option>
                        </select>
                    </div>
                    <div class="form-footer">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary w-100"
                                    onclick="traverse(3,4)">Previous Section</button>
                            </div>
                            <div class="col">
                                <button type="button" onclick="validateAdditional('individual')"
                                    class="btn btn-primary w-100">Review Information</button>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="card-body" id="card-5" style="display:none">
                    <h2 class="mb-4 text-center">Review Submitted Information</h2>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_fname_label">First Name</label>
                            <input type="text" class="form-control" name="confirm_fname">
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_mname_label">Middle Name</label>
                            <input type="text" class="form-control" name="confirm_mname">
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_lname_label">Last Name</label>
                            <input type="text" class="form-control" name="confirm_lname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_dob_label">Date of Birth</label>
                            <input type="date" class="form-control" name="confirm_dob">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_sex_label">Gender</label>
                            <select name="confirm_sex" id="" class="form-select">
                                <option value="">Please select an option</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_mail_address_label">Email
                                Address</label>
                            <input type="text" class="form-control" name="confirm_mail_address">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_trn_label">TRN</label>
                            <input type="text" class="form-control" name="confirm_trn">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_address_label">Home Address</label>
                            <input type="text" class="form-control" name="confirm_address">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_parish_label">Parish</label>
                            <select name="confirm_parish" id="" class="form-control">
                                <option value="">Please select a Parish</option>
                                <option value="Kingston">Kingston</option>
                                <option value="St.Andrew">St.Andrew</option>
                                <option value="St.Catherine">St.Catherine</option>
                                <option value="St.Thomas">St.Thomas</option>
                                <option value="Clarendon">Clarendon</option>
                                <option value="St.Elizabeth">St.Elizabeth</option>
                                <option value="Manchester">Manchester</option>
                                <option value="Westmoreland">Westmoreland</option>
                                <option value="Hanover">Hanover</option>
                                <option value="St.Ann">St.Ann</option>
                                <option value="St.Mary">St.Mary</option>
                                <option value="St.James">St.James</option>
                                <option value="Trelawny">Trelawny</option>
                                <option value="Portland">Portland</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_cell_num_label">Cell No.</label>
                            <input type="text" class="form-control" name="confirm_cell_num">
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_home_num_label">Home No.</label>
                            <input type="text" class="form-control" name="confirm_home_num">
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_work_num_label">Work No.</label>
                            <input type="text" class="form-control" name="confirm_work_num">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_occupation_label">Occupation</label>
                            <input type="text" class="form-control" name="confirm_occupation">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_employer_label">Employer
                                Name</label>
                            <input type="text" class="form-control" name="confirm_employer">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_employer_address_label">Employer
                                Address</label>
                            <input type="text" class="form-control" name="confirm_employer_address">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="" class="form-label" id="confirm_employer_parish_label">Employer
                                Parish</label>
                            <select name="confirm_employer_parish" id="" class="form-control">
                                <option value="">Please select a Parish</option>
                                <option value="Kingston">Kingston</option>
                                <option value="St.Andrew">St.Andrew</option>
                                <option value="St.Catherine">St.Catherine</option>
                                <option value="St.Thomas">St.Thomas</option>
                                <option value="Clarendon">Clarendon</option>
                                <option value="St.Elizabeth">St.Elizabeth</option>
                                <option value="Manchester">Manchester</option>
                                <option value="Westmoreland">Westmoreland</option>
                                <option value="Hanover">Hanover</option>
                                <option value="St.Ann">St.Ann</option>
                                <option value="St.Mary">St.Mary</option>
                                <option value="St.James">St.James</option>
                                <option value="Trelawny">Trelawny</option>
                                <option value="Portland">Portland</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_teacher_label">Are you a
                                teacher?</label>
                            <select name="confirm_teacher" id="" class="form-select">
                                <option value="">Enter an answer</option>
                                <option value="">Yes</option>
                                <option value="">No</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_student_label">Are you a
                                student?</label>
                            <select class="form-select" name="confirm_student">
                                <option value="">Enter an answer</option>
                                <option value="">Yes</option>
                                <option value="">No</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="" class="form-label" id="confirm_applied_label">Have you applied
                                before?</label>
                            <select name="confirm_applied" id="" class="form-select">
                                <option value="">Enter an answer</option>
                                <option value="">Yes</option>
                                <option value="">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label for="" class="form-label" id="confirm_years_label"
                                id="confirm_years_label">Years left in school
                                program</label>
                            <input type="text" class="form-control" name ="confirm_years">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="" class="form-label" id="confirm_granted_label">Did you receive
                                permit?</label>
                            <select name="confirm_granted" id="" class="form-select">
                                <option value="">Please enter an answer</option>
                                <option value="">Yes</option>
                                <option value="">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="button" class="btn btn-primary w-100" onclick="validateReview()">
                            Confirm Information</button>
                    </div>
                </div>
                <div class="card-body" id="card-6" style="display:none">
                    <h2 class="h2 text-center mb-4">Please Upload Signature</h2>
                    <form method="POST" action="#">
                        @csrf
                        <div class="col-md-12">
                            <div id="sig">
                            </div>
                            <div class="text-center">
                                <button id="clear" class="btn btn-danger" type="button">Clear Signature</button>
                            </div>
                            <textarea id="signature64" name="signed" style="display:none"></textarea>
                        </div>
                        <br />
                        <div class="form-footer">
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn btn-secondary w-100"
                                        onclick="traverse(4, 5)">Previous Section</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary w-100"
                                        onclick="validateEmployment()">Confirm Information</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/temp_online_app.js" type="text/javascript"></script>
    <script src="./tabler/dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./tabler/dist/js/demo.min.js?1692870487" defer></script>
    <script src="https://unpkg.com/imask"></script>
    <script>
        const cell_num = document.querySelector("input[name='cell_num']");
        const home_num = document.querySelector("input[name='home_num']");
        const trn = document.querySelector("input[name='trn']");
        const work_num = document.querySelector("input[name='work_num']");

        const maskOptions = {
            mask: '+1(000)000-0000'
        }

        const maskOptions2 = {
            mask: '000-000-000'
        };

        const mask1 = IMask(cell_num, maskOptions);
        const mask2 = IMask(home_num, maskOptions);
        const mask3 = IMask(trn, maskOptions2);
        const mask4 = IMask(work_num, maskOptions);

        var sig = $('#sig').signature({
            syncField: '#signature64',
            syncFormat: 'PNG'
        });

        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });
    </script>
</body>

</html>
