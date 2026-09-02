document.addEventListener("DOMContentLoaded", function() {
    var emailInput = document.getElementById("email");
    if (emailInput && document.getElementById("registerForm")) {
        emailInput.addEventListener("blur", function() {
            var val = emailInput.value.trim();
            var feedback = document.getElementById("email-ajax-feedback");
            if (val === "" || !feedback) return;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "index.php?controller=ajax&action=checkEmail&email=" + encodeURIComponent(val), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var res = JSON.parse(xhr.responseText);
                    if (res.available) {
                        feedback.style.color = "#10b981";
                        feedback.innerText = "✓ " + res.message;
                    } else {
                        feedback.style.color = "#ef4444";
                        feedback.innerText = "✗ " + res.message;
                    }
                }
            };
            xhr.send();
        });
    }

    var liveSearchInput = document.getElementById("live-search-input");
    if (liveSearchInput) {
        liveSearchInput.addEventListener("keyup", filterVehiclesLive);
    }

    var filterBtns = document.querySelectorAll(".category-filter-btn");
    filterBtns.forEach(function(btn) {
        btn.addEventListener("click", function() {
            filterBtns.forEach(function(b) {
                b.classList.remove("btn-primary");
                b.classList.add("btn-secondary");
            });
            this.classList.remove("btn-secondary");
            this.classList.add("btn-primary");
            filterVehiclesLive();
        });
    });
});

function triggerLivePriceCalc() {
    var vehicleId = document.getElementById("modal_vehicle_id").value;
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
    var calcBox = document.getElementById("total_cost_calc");

    if (!vehicleId || !startDate || !endDate) {
        if (calcBox) calcBox.classList.add("hidden");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?controller=ajax&action=calculateCost", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                document.getElementById("rental_days").innerText = res.days;
                document.getElementById("total_cost_val").innerText = res.formatted_cost;
                calcBox.classList.remove("hidden");
            } else {
                calcBox.classList.add("hidden");
            }
        }
    };
    xhr.send("vehicle_id=" + encodeURIComponent(vehicleId) + "&start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate));
}

function filterVehiclesLive() {
    var keyword = document.getElementById("live-search-input") ? document.getElementById("live-search-input").value.toLowerCase().trim() : "";
    var activeCategoryBtn = document.querySelector(".category-filter-btn.btn-primary");
    var activeCategory = activeCategoryBtn ? activeCategoryBtn.getAttribute("data-category") : "All";

    var cards = document.querySelectorAll(".vehicle-item-card");
    cards.forEach(function(card) {
        var cardCategory = card.getAttribute("data-category");
        var cardText = card.getAttribute("data-text");

        var matchesCategory = (activeCategory === "All" || cardCategory === activeCategory);
        var matchesText = (keyword === "" || cardText.indexOf(keyword) !== -1);

        if (matchesCategory && matchesText) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

function ajaxApproveRental(rentalId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?controller=ajax&action=approveRental", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                var badge = document.getElementById("rental-badge-" + rentalId);
                if (badge) {
                    badge.className = "badge badge-available";
                    badge.innerText = "Rented";
                }
                var actions = document.getElementById("rental-actions-" + rentalId);
                if (actions) {
                    actions.innerHTML = '<button class="btn btn-primary btn-sm" onclick="ajaxReturnRental(' + rentalId + ')">Process Return</button>';
                }
            } else {
                alert(res.message);
            }
        }
    };
    xhr.send("id=" + encodeURIComponent(rentalId));
}

function ajaxReturnRental(rentalId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?controller=ajax&action=returnRental", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                var badge = document.getElementById("rental-badge-" + rentalId);
                if (badge) {
                    badge.className = "badge badge-available";
                    badge.innerText = "Returned";
                }
                var actions = document.getElementById("rental-actions-" + rentalId);
                if (actions) {
                    actions.innerHTML = '<span class="text-muted">Completed</span>';
                }
            } else {
                alert(res.message);
            }
        }
    };
    xhr.send("id=" + encodeURIComponent(rentalId));
}
