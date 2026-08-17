<div class="card border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="text-muted mb-0">Appointment Information</h4>
        @if ($permit_application->establishment_clinic_id == '' && count($appointments) == 0)
            <button type="button" class="btn btn-primary"
                onclick="addAppointment({{ json_encode($appointment_available) }}, {{ json_encode($permit_application->id) }} )">
                Add Appointment
            </button>
        @endif
    </div>

    <div class="card-body">
        @include('partials.tables.permit_applications_appointments_table')
    </div>
</div>
