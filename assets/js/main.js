document.addEventListener("DOMContentLoaded", function() {
    var loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.onsubmit = validateLoginForm;
    }

    var registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.onsubmit = validateRegisterForm;
    }

    var vehicleForm = document.getElementById("vehicleForm");
    if (vehicleForm) {
        vehicleForm.onsubmit = validateVehicleForm;
    }

    var startInput = document.getElementById("start_date");
    var endInput = document.getElementById("end_date");
    if (startInput && endInput) {
        startInput.addEventListener("change", triggerLivePriceCalc);
        endInput.addEventListener("change", triggerLivePriceCalc);
    }
});

function validateLoginForm() {
    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();

    if (email === "") {
        alert("Email address cannot be empty");
        return false;
    }
    if (password === "") {
        alert("Password cannot be empty");
        return false;
    }
    return true;
}

function validateRegisterForm() {
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var address = document.getElementById("address").value.trim();
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if (name === "") {
        alert("Full Name is required");
        return false;
    }
    if (email === "") {
        alert("Email address is required");
        return false;
    }
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }
    if (phone === "") {
        alert("Phone number is required");
        return false;
    }
    if (address === "") {
        alert("Address is required");
        return false;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters long");
        return false;
    }
    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }
    return true;
}

function validateVehicleForm() {
    var brand = document.getElementById("veh_brand").value.trim();
    var model = document.getElementById("veh_model").value.trim();
    var plate = document.getElementById("veh_plate").value.trim();
    var rate = parseFloat(document.getElementById("veh_rate").value);

    if (brand === "" || model === "" || plate === "") {
        alert("Please fill in Brand, Model, and Plate Number");
        return false;
    }
    if (isNaN(rate) || rate <= 0) {
        alert("Please enter a valid daily rate greater than zero");
        return false;
    }
    return true;
}

function handleBookingSubmit(event) {
    var startVal = document.getElementById("start_date").value;
    var endVal = document.getElementById("end_date").value;

    if (!startVal || !endVal) {
        alert("Please select both pickup and return dates");
        event.preventDefault();
        return false;
    }

    var d1 = new Date(startVal);
    var d2 = new Date(endVal);
    if (d2 < d1) {
        alert("Return date must be after pickup date");
        event.preventDefault();
        return false;
    }
    return true;
}

function openModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
        modal.style.display = "flex";
    }
}

function closeModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
        modal.style.display = "none";
    }
}

function openBookingModal(id, title, rate) {
    document.getElementById("modal_vehicle_id").value = id;
    document.getElementById("modal_veh_title").innerText = title;
    var rateEl = document.getElementById("daily_rate_val");
    rateEl.innerText = rate + " BDT";
    rateEl.setAttribute("data-rate", rate);
    
    var calcBox = document.getElementById("total_cost_calc");
    if (calcBox) {
        calcBox.classList.add("hidden");
    }
    openModal("booking-modal");
}

function openAddVehicleModal() {
    document.getElementById("vehicle-modal-title").innerText = "Add Vehicle Record";
    document.getElementById("veh_id").value = "";
    document.getElementById("vehicleForm").action = "index.php?controller=vehicles&action=add";
    document.getElementById("veh_brand").value = "";
    document.getElementById("veh_model").value = "";
    document.getElementById("veh_plate").value = "";
    document.getElementById("veh_rate").value = "";
    document.getElementById("veh_submit_btn").innerText = "Save Vehicle";
    openModal("vehicle-modal");
}

function openEditVehicleModal(veh) {
    document.getElementById("vehicle-modal-title").innerText = "Edit Vehicle Record";
    document.getElementById("veh_id").value = veh.id;
    document.getElementById("vehicleForm").action = "index.php?controller=vehicles&action=edit";
    document.getElementById("veh_brand").value = veh.brand;
    document.getElementById("veh_model").value = veh.model;
    document.getElementById("veh_plate").value = veh.plate_number;
    document.getElementById("veh_type").value = veh.type;
    document.getElementById("veh_year").value = veh.year;
    document.getElementById("veh_rate").value = veh.daily_rate;
    document.getElementById("veh_status").value = veh.status;
    document.getElementById("veh_submit_btn").innerText = "Update Vehicle";
    openModal("vehicle-modal");
}
