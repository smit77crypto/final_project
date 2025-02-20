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
        showError("gameNameError", "Game name is required.");
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
        showError(errorId, "Price is required.");
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
        showError(errorId, "Only image files are allowed.");
        input.value = ""; // Reset the file input
    } else {
        hideError(errorId);
    }
}

// Function to Validate Selected Slots
function validateSlots() {
    if (selectedSlots.length === 0) {
        showError("slotError", "At least one time slot must be selected.");
        return false;
    } else {
        hideError("slotError");
        return true;
    }
}

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

// Update Selected Slots
function updateSelectedSlots() {
    selectedSlots = [];
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
        selectedSlots.push(checkbox.value);
    });

    document.getElementById('selected-list').innerHTML = selectedSlots.map(slot => `
        <div class="selected-slot">
            ${slot}
            <span class="remove-slot" onclick="removeSlot('${slot}')"><i class="fa-solid fa-trash-can"></i></span>
        </div>
    `).join('');

    validateSlots(); // Validate selection in real-time
}

// Remove Slot
function removeSlot(slot) {
    document.querySelector(`input[value="${slot}"]`).checked = false;
    updateSelectedSlots();
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

    alert("Game information submitted successfully!");
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
window.onload = function() {
    hideForm(); // Ensure the form is hidden on load
};
