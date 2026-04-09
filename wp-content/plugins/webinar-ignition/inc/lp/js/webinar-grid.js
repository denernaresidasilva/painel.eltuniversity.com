// // Only define if not already defined
// if (typeof window.WiGrid === "undefined") {
//   class WiGrid {
//     constructor() {
//       this.initDropdowns();
//       this.bindOutsideClick();
//       this.bindDropdownSelection();
//     }

//     // Initialize dropdown toggle logic
//     initDropdowns() {
//       const buttons = document.querySelectorAll(".wi-dropdown-btn");

//       buttons.forEach((button) => {
//         button.addEventListener("click", (e) => {
//           e.stopPropagation();
//           this.closeAllDropdowns(button.nextElementSibling);

//           const dropdown = button.nextElementSibling;
//           dropdown.style.display =
//             dropdown.style.display === "block" ? "none" : "block";
//         });
//       });
//     }

//     // Close all dropdowns except the active one
//     closeAllDropdowns(exception = null) {
//       document.querySelectorAll(".wi-dropdown-menu").forEach((menu) => {
//         if (menu !== exception) {
//           menu.style.display = "none";
//         }
//       });
//     }

//     // Close dropdowns when clicking outside
//     bindOutsideClick() {
//       document.addEventListener("click", () => {
//         this.closeAllDropdowns();
//       });
//     }

//     bindDropdownSelection() {
//       document.querySelectorAll(".wi-dropdown-menu a").forEach((item) => {
//         item.addEventListener("click", (e) => {
//           e.preventDefault(); // prevent navigation
//           const selectedText = item.textContent;
//           const dropdownMenu = item.closest(".wi-dropdown-menu");
//           const button = dropdownMenu.previousElementSibling;

//           // Set button text to selected value
//           button.textContent = selectedText;
//           console.log("wi grid filter user selection", selectedText);

//           // Close the dropdown
//           dropdownMenu.style.display = "none";
//         });
//       });
//     }
//   }

//   // Expose globally
//   window.WiGrid = WiGrid;
// }

// // Auto-init if needed
// document.addEventListener("DOMContentLoaded", () => {
//   if (!window.wiGridInstance) {
//     window.wiGridInstance = new WiGrid();
//   }
// });
