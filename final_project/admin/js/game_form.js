function getSelectedSlots() {
    let selectedSlot = [];
    const slotCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    slotCheckboxes.forEach((checkbox) => {
        selectedSlot.push(checkbox.value);
    });
    return selectedSlot.join(","); // Store the slots as a comma-separated string
}
function getFilterSlots() {
    let filterSlots = [];
    const slotCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    slotCheckboxes.forEach((checkbox) => {
        filterSlots.push(checkbox.getAttribute('data-type'));
    });
    return filterSlots.join(","); // Store the slots as a comma-separated string
}
document.querySelector('.btn-add').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent form submission

    // Validation
    let isValid = true;
    const gameName = document.getElementById("gameName").value;
    if (!gameName) {
        document.getElementById("gameNameError").textContent = "Please Enter GameName";
        isValid = false;
    } else {
        document.getElementById("gameNameError").textContent = "";
    }

    const price30 = document.getElementById("price30").value;
    if (!price30) {
        document.getElementById("price30Error").textContent = "Please Enter Price";
        isValid = false;
    } else if (isNaN(price30) || parseFloat(price30) <= 0 || !/^\d+(\.\d{1,2})?$/.test(price30)) {
        document.getElementById("price30Error").textContent = "Please enter a valid positive price for 30 mins (no letters allowed).";
        isValid = false;
    } else {
        document.getElementById("price30Error").textContent = "";
    }

    const price60 = document.getElementById("price60").value;
    if (!price60) {
        document.getElementById("price60Error").textContent = "Please Enter Price";
        isValid = false;
    } else if (isNaN(price60) || parseFloat(price60) <= 0 || !/^\d+(\.\d{1,2})?$/.test(price60)) {
        document.getElementById("price60Error").textContent = "Please enter a valid positive price for 1 hour (no letters allowed).";
        isValid = false;
    } else {
        document.getElementById("price60Error").textContent = "";
    }

    const selectedSlots = getSelectedSlots(); // You should define this function to get selected slots
    if (selectedSlots === "") {
        document.getElementById("slotError").textContent = "Please select at least one time slot.";
        document.getElementById("slotError").style.display = "block"; // Show error message
        isValid = false;
    } else {
        document.getElementById("slotError").style.display = "none"; // Hide error message
    }
    if (isValid) {
        const selectedSlots = getSelectedSlots();
        const filter = getFilterSlots();
        const gameImage = document.getElementById("gameImage").files[0];
        const sliderImage = document.getElementById("sliderImage").files[0];
        const gameId = document.getElementById("gameId").value;  // Get the gameId from your form if it's available for update
        // Form data preparation
        const formData = new FormData();
        formData.append("gameName", gameName);
        formData.append("price30", price30);
        formData.append("price60", price60);
        formData.append("gameImage", gameImage);
        formData.append("sliderImage", sliderImage);
        formData.append("slots", selectedSlots);
        formData.append("filter", filter);
        formData.append("gameId", gameId);  // Append the gameId for update
        console.log(filter);
        // AJAX Request to PHP backend
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../game_management/game_data.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                // Redirect after successful submission
                window.location.href = "../game_management.php";  // Redirect to the game management page
            } else {
                alert("Error: " + xhr.responseText);
            }
        };
        xhr.send(formData);
    }

});
let selectedSlots = [];

// Validate Input Fields in Real-time
document.getElementById("gameName").addEventListener("input", validateGameName);
document.getElementById("price30").addEventListener("input", validatePrice);
document.getElementById("price60").addEventListener("input", validatePrice);
document.getElementById("gameImage").addEventListener("change", validateImage);
document.getElementById("sliderImage").addEventListener("change", validateImage);

// Function to Validate Game Name
function validateGameName() {
    const gameName = document.getElementById("gameName").value.trim();
    const error = document.getElementById("gameNameError");

    if (!gameName) {
        showError("gameNameError", "Please Enter GameName");
    } else if (gameName.length < 3) {
        showError("gameNameError", "Game name must be at least 3 characters.");
    } else {
        hideError("gameNameError");
    }
}

// Function to Validate Prices
function validatePrice(event) {
    const input = event.target;
    const value = input.value.trim();
    const errorId = input.id === "price30" ? "price30Error" : "price60Error";

    if (!value) {
        showError(errorId, "Please Enter Price");
    } else if (isNaN(value) || parseFloat(value) <= 0) {
        showError(errorId, "Enter a valid positive number.");
    } else {
        hideError(errorId);
    }
}

// Function to Validate Image Uploads
function validateImage(event) {
    const input = event.target;
    const file = input.files[0];
    const errorId = input.id === "gameImage" ? "gameImageError" : "sliderImageError";

    if (!file) {
        showError(errorId, "Please upload an image.");
    } else if (!file.type.startsWith("image/")) {
        showError(errorId, "Only .png, .jpg and .jpeg files are allowed.");
        input.value = ""; // Reset the file input
    } else {
        hideError(errorId);
    }
}

// Function to Validate Selected Slots
// function validateSlots() {
//     if (selectedSlots.length === 0) {
//         showError("slotError", "At least one time slot must be selected.");
//         return false;
//     } else {
//         hideError("slotError");
//         return true;
//     }
// }

// Filter Slots
// Filter Slots
function filterSlots(type) {
    document.querySelectorAll('.slot-item input').forEach(input => {
        const slotType = input.getAttribute('data-type');
        if (type === 'both' || slotType === type) {
            input.parentElement.style.display = 'flex';
        } else {
            input.parentElement.style.display = 'none';
        }
    });
}
window.onload = function () {
    updateSelectedSlots(); // Ensure selected slots are displayed when the page loads
};
// Update Selected Slots
function updateSelectedSlots() {
    selectedSlots = [];
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
        selectedSlots.push(checkbox.value);
    });

    document.getElementById('selected-list').innerHTML = selectedSlots.map(slot => `
        <div class="selected-slot">
            ${slot} <span class="remove-slot" onclick="removeSlot('${slot}')"><i class="fa-solid fa-trash-can"></i></span>
        </div>
    `).join('');

    validateSlots(); // Validate selection in real-time
}

// Save Game with Validation
function saveGame() {
    validateGameName();
    validatePrice({ target: document.getElementById("price30") });
    validatePrice({ target: document.getElementById("price60") });
    validateImage({ target: document.getElementById("gameImage") });
    validateImage({ target: document.getElementById("sliderImage") });

    if (document.querySelectorAll(".error-message:not([style='display: none;'])").length > 0) {
        alert("Please fix validation errors before submitting.");
        return;
    }

    resetForm();
}

// Reset Form
function resetForm() {
    document.querySelectorAll('input[type="text"], input[type="file"]').forEach(input => input.value = "");
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
    selectedSlots = [];
    document.getElementById("selected-list").innerHTML = "";
    hideForm();
    document.querySelectorAll(".error-message").forEach(error => error.style.display = "none");
}
function removeSlot(slot) {
    document.querySelector(`input[value="${slot}"]`).checked = false;
    updateSelectedSlots();
}
// Helper Functions to Show and Hide Errors
function showError(id, message) {
    const errorElement = document.getElementById(id);
    if (!errorElement) return;
    errorElement.innerText = message;
    errorElement.style.display = "block";
}

function hideError(id) {
    const errorElement = document.getElementById(id);
    if (!errorElement) return;
    errorElement.style.display = "none";
}

// Initialize on Load
