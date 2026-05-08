<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
    --primary:#004a99;
    --light-blue:#eef5ff;
    --border:#e4e7ec;
    --text:#333;
    --muted:#666;
}

body{
    font-family: DejaVu Sans, Arial, sans-serif;
    background:#f2f4f7;
    padding:20px;
}

.card{
    max-width:650px;
    margin:auto;
    background:#fff;
    border-radius:18px;
    padding:26px;
    border:1px solid var(--border);
}

/* HEADER */
.header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:3px solid var(--primary);
    padding-bottom:14px;
    margin-bottom:18px;
}
.header img{height:70px;}

.title{
    text-align:center;
    flex:1;
}
.title h1{
    font-size:18px;
    margin:0;
    color:var(--primary);
    letter-spacing:.5px;
}
.title small{
    font-size:10px;
    color:var(--muted);
}

/* MAIN CONTENT */
.main{
    display:flex;
    gap:18px;
}

.details{
    flex:1;
}

.row{
    display:flex;
    padding:7px 0;
    border-bottom:1px dashed #eee;
}
.label{
    width:95px;
    font-weight:bold;
    color:var(--primary);
}
.value{
    font-weight:600;
}

.photo{
    width:130px;
    height:130px;
    border:2px solid #ccc;
    border-radius:14px;
    overflow:hidden;
}
.photo img{width:100%;height:100%;object-fit:cover;}

/* SECTION TITLES */
.section-title{
    margin-top:18px;
    font-size:13px;
    font-weight:bold;
    color:var(--primary);
    border-bottom:2px solid var(--primary);
    padding-bottom:4px;
}

/* TEST RESULTS GRID */
.results-grid{
    margin-top:10px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    font-size:12px;
}
.test{
    background:var(--light-blue);
    padding:10px;
    border-radius:8px;
    border-left:4px solid var(--primary);
}

/* APPROVAL BOX */
.approval{
    margin-top:15px;
    background:#f7fbff;
    border-left:5px solid #28a745;
    padding:14px;
    font-size:12px;
    line-height:1.6;
}

.badge{
    background:#28a745;
    color:#fff;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}

/* QR AREA */
.qr{
    text-align:center;
    margin-top:18px;
    border-top:1px dashed #ddd;
    padding-top:12px;
    font-size:11px;
}

.qr img{width:90px;}

</style>
</head>

<body>
<div class="card">

    <!-- HEADER -->
    <div class="header">
        <img src="{{ public_path('images/coatofarms.png') }}">
        <div class="title">
            <h1>MINISTRY OF HEALTH & WELLNESS</h1>
            <small>Public Health (Food Handling 1998) Regulations 26–31</small>
        </div>
        <img src="{{ public_path('images/mohlogo.png') }}">
    </div>

    <!-- BASIC DETAILS -->
    <div class="main">
        <div class="details">
            <div class="row"><div class="label">Category:</div><div class="value">{{ $applicant->permitCategory->name ?? 'Basic Foodhandlers' }}</div></div>
            <div class="row"><div class="label">Name:</div><div class="value">{{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</div></div>
            <div class="row"><div class="label">Permit #:</div><div class="value">{{ $applicant->permit_no ?? 'Pending' }}</div></div>
            <div class="row"><div class="label">Issued:</div><div class="value">{{ \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') }}</div></div>
            <div class="row"><div class="label">Expires:</div><div class="value" style="color:#d9534f">{{ \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') }}</div></div>
        </div>

        <div class="photo">
            <img src="{{ public_path('storage/' . $applicant->photo_upload) }}">
        </div>
    </div>

    <!-- TEST RESULTS -->
    <div class="section-title">MEDICAL TEST RESULTS</div>
    <div class="results-grid">
        <div class="test"><b>Medical Exam:</b> Passed</div>
        <div class="test"><b>Food Handler Training:</b> Completed</div>
        <div class="test"><b>Interview:</b> Approved</div>
        <div class="test"><b>Status:</b> Fit for Food Handling</div>
    </div>

    <!-- APPROVAL -->
    <div class="approval">
        <span class="badge">OFFICIALLY VERIFIED</span><br><br>
        This applicant has successfully completed all required medical examinations
        and has been approved by the Medical Officer of Health. The holder is legally
        certified to handle food in accordance with national public health regulations.
    </div>

    {{-- <!-- QR CODE -->
    <div class="qr">
        <img src="{{ public_path('images/qrcode.png') }}">
        <div>Scan to verify this permit</div>
    </div> --}}

</div>
</body>
</html>