// Function to update filled state
function updateFilledState(input) {
  if (input.value.trim() !== "") {
    input.classList.add("filled");
    console.log(`Added 'filled' to ${input.id} with value: ${input.value}`);
  } else {
    input.classList.remove("filled");
    console.log(`Removed 'filled' from ${input.id} with value: ${input.value}`);
  }
}

// Add 'filled' class to inputs when they have a value
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll(".input-group input").forEach((input) => {
    // Check on page load if the input has a value
    updateFilledState(input);

    // Add/remove 'filled' class on input change
    input.addEventListener("input", () => {
      updateFilledState(input);
    });

    // Ensure the class is updated on blur to catch any changes
    input.addEventListener("blur", () => {
      updateFilledState(input);
    });

    // Also check on focus in case of autofill
    input.addEventListener("focus", () => {
      updateFilledState(input);
    });
  });

  // Check for browser autofill after a short delay
  setTimeout(() => {
    document.querySelectorAll(".input-group input").forEach((input) => {
      updateFilledState(input);
    });
  }, 100);
});
