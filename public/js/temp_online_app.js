function traverse(new_card, old_card) {
    document.getElementById("card-" + old_card).style.display = "none";
    document.getElementById("card-" + new_card).style.display = "";
}

function validatePersonal(validationType) {
    const maxDate = new Date(
        new Date().setFullYear(new Date().getFullYear() - 12)
    );
    const minDate = new Date(
        new Date().setFullYear(new Date().getFullYear() - 100)
    );

    var prefix_string = validationType == "individual" ? "" : "confirm_";

    let fname = document.querySelector(
        "input[name='" + prefix_string + "fname']"
    );
    let mname = document.querySelector(
        "input[name='" + prefix_string + "mname']"
    );
    let lname = document.querySelector(
        "input[name='" + prefix_string + "lname']"
    );
    let dob = document.querySelector("input[name='" + prefix_string + "dob']");
    let sex = document.querySelector("select[name='" + prefix_string + "sex']");
    const nameRegex = new RegExp(/^[A-Za-zs|-|']+$/);

    //First Name
    if (fname.value != "") {
        if (nameRegex.test(fname.value)) {
            removeError(prefix_string + "fname", fname);
        } else {
            createErrorMessage(
                prefix_string + "fname",
                prefix_string == "" ? "Invalid characters entered" : "Invalid",
                fname
            );
        }
    } else {
        createErrorMessage(
            prefix_string + "fname",
            prefix_string == "" ? "This is a required field" : "Required",
            fname
        );
    }

    //Middle Name
    if (mname.value == "") {
        removeError(prefix_string + "mname", mname);
    } else {
        if (nameRegex.test(mname.value)) {
            removeError(prefix_string + "mname", mname);
        } else {
            createErrorMessage(
                prefix_string + "mname",
                prefix_string == "" ? "Invalid characters entered" : "Invalid",
                mname
            );
        }
    }

    // //Last Name
    if (lname.value != "") {
        if (nameRegex.test(lname.value)) {
            removeError(prefix_string + "lname", lname);
        } else {
            createErrorMessage(
                prefix_string + "lname",
                prefix_string == "" ? "Invalid characters entered" : "Invalid",
                lname
            );
        }
    } else {
        createErrorMessage(
            prefix_string + "lname",
            prefix_string == "" ? "This is a required field" : "Required",
            lname
        );
    }

    //Date of Birth
    if (dob.value != "") {
        dob_converted = new Date(dob.value);
        if (dob_converted > maxDate || dob_converted < minDate) {
            createErrorMessage(
                prefix_string + "dob",
                "Invalid date entered",
                dob
            );
        } else {
            removeError(prefix_string + "dob", dob);
        }
    } else {
        createErrorMessage(
            prefix_string + "dob",
            "This is a required field",
            dob
        );
    }

    //Gender
    if (sex.selectedIndex == 0) {
        createErrorMessage(
            prefix_string + "sex",
            "This is a required field",
            sex
        );
    } else {
        removeError("sex", sex);
    }

    if (
        !document.getElementById(prefix_string + "fname_error") &&
        !document.getElementById(prefix_string + "mname_error") &&
        !document.getElementById(prefix_string + "lname_error") &&
        !document.getElementById(prefix_string + "dob_error") &&
        !document.getElementById(prefix_string + "sex_error")
    ) {
        if (validationType == "individual") {
            populateConfirmation();
            traverse(2, 1);
        } else {
            return true;
        }
    } else if (validationType != "individual") {
        return false;
    }
}

function validateContact(validationType) {
    var prefix_string = validationType == "individual" ? "" : "confirm_";

    mail_address = document.querySelector(
        "input[name='" + prefix_string + "mail_address']"
    );
    let cell_num = document.querySelector(
        "input[name='" + prefix_string + "cell_num']"
    );
    let home_num = document.querySelector(
        "input[name='" + prefix_string + "home_num']"
    );
    address = document.querySelector(
        "input[name='" + prefix_string + "address']"
    );
    parish = document.querySelector(
        "select[name='" + prefix_string + "parish'"
    );
    const emailRegex = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
    const phoneRegex = new RegExp(/^\++1+\(+[0-9{3}]+\)+[0-9{3}]+-+[0-9]{4}$/);
    const addressRegex = new RegExp(/^[A-Za-z0-9\s|-|,|'|.]+$/);

    if (mail_address.value != "") {
        if (emailRegex.test(mail_address.value)) {
            removeError(prefix_string + "mail_address", mail_address);
        } else {
            createErrorMessage(
                prefix_string + "mail_address",
                "Please enter valid email",
                mail_address
            );
        }
    } else {
        createErrorMessage(
            prefix_string + "mail_address",
            "This is a required field",
            mail_address
        );
    }

    if (cell_num.value != "") {
        if (phoneRegex.test(cell_num.value)) {
            removeError(prefix_string + "cell_num", cell_num);
        } else {
            createErrorMessage(
                prefix_string + "cell_num",
                prefix_string == ""
                    ? "Please enter valid phone number"
                    : "Invalid",
                cell_num
            );
        }
    } else {
        createErrorMessage(
            prefix_string + "cell_num",
            prefix_string == "" ? "This is a required field" : "Required",
            cell_num
        );
    }

    if (home_num.value == "") {
        removeError(prefix_string + "home_num", home_num);
    } else {
        if (phoneRegex.test(home_num.value)) {
            removeError(prefix_string + "home_num", home_num);
        } else {
            createErrorMessage(
                prefix_string + "home_num",
                prefix_string == ""
                    ? "Please enter valid home phone number"
                    : "Invalid",
                home_num
            );
        }
    }

    if (address.value != "") {
        if (addressRegex.test(address.value)) {
            removeError(prefix_string + "address", address);
        } else {
            createErrorMessage(
                prefix_string + "address",
                "Enter valid address",
                address
            );
        }
    } else {
        createErrorMessage(
            prefix_string + "address",
            "This is a required field",
            address
        );
    }

    if (parish.selectedIndex != 0) {
        removeError(prefix_string + "parish", parish);
    } else {
        createErrorMessage(
            prefix_string + "parish",
            "This is a required field",
            parish
        );
    }

    if (
        !document.getElementById(prefix_string + "mail_address_error") &&
        !document.getElementById(prefix_string + "cell_num_error") &&
        !document.getElementById(prefix_string + "home_num_error") &&
        !document.getElementById(prefix_string + "address_error") &&
        !document.getElementById(prefix_string + "parish_error")
    ) {
        if (validationType == "individual") {
            traverse(3, 2);
        } else {
            return true;
        }
    } else if (validationType != "individual") {
        return false;
    }
}

function validateEmployment(validationType) {
    var prefix_string = validationType == "individual" ? "" : "confirm_";

    let trn = document.querySelector("input[name='" + prefix_string + "trn']");
    occupation = document.querySelector(
        "input[name='" + prefix_string + "occupation']"
    );
    employer = document.querySelector(
        "input[name='" + prefix_string + "employer']"
    );
    employer_address = document.querySelector(
        "input[name='" + prefix_string + "employer_address']"
    );
    employer_parish = document.querySelector(
        "select[name='" + prefix_string + "employer_parish'"
    );
    let work_num = document.querySelector(
        "input[name='" + prefix_string + "work_num'"
    );
    trnRegex = new RegExp(/^[0-9{3}]+-+[0-9{3}]+-+[0-9]{3}$/);
    const addressRegex = new RegExp(/^[A-Za-z0-9\s|-|,|'|.]+$/);
    const phoneRegex = new RegExp(/^\++1+\(+[0-9{3}]+\)+[0-9{3}]+-+[0-9]{4}$/);

    if (trn.value != "") {
        if (trnRegex.test(trn.value)) {
            removeError(prefix_string + "trn", trn);
        } else {
            createErrorMessage(prefix_string + "trn", "Enter a valid trn", trn);
        }
    } else {
        createErrorMessage(
            prefix_string + "trn",
            "This is a required field",
            trn
        );
    }

    if (occupation.value != "") {
        removeError(prefix_string + "occupation", occupation);
    } else {
        createErrorMessage(
            prefix_string + "occupation",
            "This is a required field",
            occupation
        );
    }

    if (employer.value != "") {
        removeError(prefix_string + "employer", employer);
    } else {
        createErrorMessage(
            prefix_string + "employer",
            "This is a required field",
            employer
        );
    }

    if (employer_address.value != "") {
        if (addressRegex.test(employer_address.value)) {
            removeError(prefix_string + "employer_address", employer_address);
        } else {
            createErrorMessage(
                prefix_string + "employer_address",
                "Enter valid address",
                employer_address
            );
        }
    } else {
        createErrorMessage(
            prefix_string + "employer_address",
            "Required field",
            employer_address
        );
    }

    if (employer_parish.selectedIndex != 0) {
        removeError(prefix_string + "employer_parish", employer_parish);
    } else {
        createErrorMessage(
            prefix_string + "employer_parish",
            "This is a required field",
            employer_parish
        );
    }

    if (work_num.value != "") {
        if (phoneRegex.test(work_num.value)) {
            removeError(prefix_string + "work_num", work_num);
        } else {
            createErrorMessage(
                prefix_string + "work_num",
                prefix_string == "" ? "Enter a valid phone number" : "Invalid",
                work_num
            );
        }
    } else {
        removeError(prefix_string + "work_num", work_num);
    }
    // else {
    //     createErrorMessage(
    //         prefix_string + "work_num",
    //         "This is a required field",
    //         work_num
    //     );
    // }

    if (
        !document.getElementById(prefix_string + "trn_error") &&
        !document.getElementById(prefix_string + "occupation_error") &&
        !document.getElementById(prefix_string + "employer_error") &&
        !document.getElementById(prefix_string + "employer_address_error") &&
        !document.getElementById(prefix_string + "employer_parish_error") &&
        !document.getElementById(prefix_string + "work_num_error")
    ) {
        if (validationType == "individual") {
            traverse(4, 3);
        } else {
            return true;
        }
    } else if (validationType != "individual") {
        return false;
    }
}

function validateAdditional(validationType) {
    var prefix_string = validationType == "individual" ? "" : "confirm_";

    teacher = document.querySelector(
        "select[name='" + prefix_string + "teacher']"
    );
    student = document.querySelector(
        "select[name='" + prefix_string + "student']"
    );
    applied = document.querySelector(
        "select[name='" + prefix_string + "applied']"
    );
    years = document.querySelector("input[name='" + prefix_string + "years']");
    granted = document.querySelector(
        "select[name='" + prefix_string + "granted'"
    );

    if (teacher.selectedIndex != 0) {
        removeError(prefix_string + "teacher", teacher);
    } else {
        createErrorMessage(
            prefix_string + "teacher",
            prefix_string == "" ? "This is a required field" : "*",
            teacher
        );
    }

    if (student.selectedIndex != 0) {
        removeError(prefix_string + "student", student);
    } else {
        createErrorMessage(
            prefix_string + "student",
            prefix_string == "" ? "This is a required field" : "*",
            student
        );
    }

    if (applied.selectedIndex != 0) {
        removeError(prefix_string + "applied", applied);
    } else {
        createErrorMessage(
            prefix_string + "applied",
            prefix_string == "" ? "This is a required field" : "*",
            applied
        );
    }

    if (student.selectedIndex == 1) {
        if (years.value != "") {
            removeError(prefix_string + "years", years);
        } else {
            createErrorMessage(
                prefix_string + "years",
                prefix_string == "" ? "This is a required field" : "Required",
                years
            );
        }
    } else {
        removeError(prefix_string + "years", years);
    }

    if (applied.selectedIndex == 1) {
        if (granted.selectedIndex != 0) {
            removeError(prefix_string + "granted", granted);
        } else {
            createErrorMessage(prefix_string + "granted", "Required", granted);
        }
    } else {
        removeError(prefix_string + "granted", granted);
    }

    if (
        !document.getElementById(prefix_string + "teacher_error") &&
        !document.getElementById(prefix_string + "student_error") &&
        !document.getElementById(prefix_string + "applied_error") &&
        !document.getElementById(prefix_string + "years_error") &&
        !document.getElementById(prefix_string + "granted_error")
    ) {
        if (validationType == "individual") {
            populateConfirmation();
            traverse(5, 4);
        } else {
            return true;
        }
    } else if (validationType != "individual") {
        return false;
    }
}

//Confirm
function validateReview() {
    if (
        validatePersonal("confirmation") &&
        validateContact("confirmation") &&
        validateEmployment("confirmation") &&
        validateAdditional("confirmation")
    ) {
        traverse(6, 5);
    }
}

function createErrorMessage(field, error, input) {
    if (!document.getElementById(field + "_error")) {
        node = document.createElement("span");
        node.innerHTML = error;
        node.style.color = "red";
        node.style.marginLeft = "10px";
        node.setAttribute("id", field + "_error");
        document.getElementById(field + "_label").appendChild(node);
        input.classList.add("is-invalid");
    } else {
        changeErrorMessage(field, error);
    }
}

function changeErrorMessage(field, new_message) {
    document.getElementById(field + "_error").innerHTML = new_message;
}

function removeError(field, input) {
    if (document.getElementById(field + "_error")) {
        document
            .getElementById(field + "_label")
            .removeChild(document.getElementById(field + "_error"));
        input.classList.remove("is-invalid");
    }
}

function populateConfirmation() {
    inputs = document.querySelectorAll(
        "div#card-1 input, div#card-2 input, div#card-3 input, div#card-4 input"
    );
    inputs.forEach((element) => {
        document.querySelector(
            "input[name='confirm_" + element.name + "']"
        ).value = element.value;
    });

    selects = document.querySelectorAll(
        "div#card-1 select, div#card-2 select, div#card-3 select, div#card-4 select"
    );

    selects.forEach((element) => {
        document.querySelector(
            "select[name='confirm_" + element.name + "']"
        ).selectedIndex = element.selectedIndex;
    });
}