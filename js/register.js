const passwordInput = document.getElementById("customPasswordInput");
const repeatPasswordInput = document.getElementById(
  "customRepeatPasswordInput"
);

const emailInput = document.getElementById("customEmailInput");
const usernameInput = document.getElementById("customUserNameInput");
const firstNameInput = document.getElementById("customFirstNameInput");
const lastNameInput = document.getElementById("customLastNameInput");
const registerForm = document.getElementById("registerForm");
const myModal = document.getElementById("myModal");

var valid_password = false;
var valid_userName = false;
var valid_firstName = false;
var valid_lastName = false;
var valid_mail = false;

usernameInput.addEventListener("input", function () {
  const userName = usernameInput.value.trim();
  if (userName === "") {
    usernameInput.classList.remove("is-valid");
    usernameInput.classList.add("is-invalid");
    valid_userName = false;
  } else {
    usernameInput.classList.remove("is-invalid");
    usernameInput.classList.add("is-valid");
    valid_userName = true;
  }
});

firstNameInput.addEventListener("input", function () {
  const firstName = usernameInput.value.trim();
  if (firstName === "" && !/^[a-zA-Z\s]+$/.test(firstName)) {
    firstNameInput.classList.remove("is-valid");
    firstNameInput.classList.add("is-invalid");
    valid_firstName = false;
  } else {
    firstNameInput.classList.remove("is-invalid");
    firstNameInput.classList.add("is-valid");
    valid_firstName = true;
  }
});

lastNameInput.addEventListener("input", function () {
  const lastName = usernameInput.value.trim();
  if (lastName === "" && !/^[a-zA-Z\s]+$/.test(lastName)) {
    lastNameInput.classList.remove("is-valid");
    lastNameInput.classList.add("is-invalid");
    valid_lastName = false;
  } else {
    lastNameInput.classList.remove("is-invalid");
    lastNameInput.classList.add("is-valid");
    valid_lastName = true;
  }
});

emailInput.addEventListener("input", function () {
  const email = emailInput.value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "" || !emailRegex.test(email)) {
    emailInput.classList.remove("is-valid");
    emailInput.classList.add("is-invalid");
    valid_mail = false;
  } else {
    emailInput.classList.remove("is-invalid");
    emailInput.classList.add("is-valid");
    valid_mail = true;
  }
});

passwordInput.addEventListener("input", function () {
  const password = passwordInput.value;
  const passwordStrength = checkPasswordStrength(password);
  if (passwordStrength) {
    passwordInput.classList.remove("is-invalid");
    passwordInput.classList.add("is-valid");
  } else {
    passwordInput.classList.remove("is-valid");
    passwordInput.classList.add("is-invalid");
  }
  if (
    repeatPasswordInput.value == passwordInput.value &&
    repeatPasswordInput.value !== ""
  ) {
    repeatPasswordInput.classList.remove("is-invalid");
    repeatPasswordInput.classList.add("is-valid");
  } else {
    repeatPasswordInput.classList.remove("is-valid");
    repeatPasswordInput.classList.add("is-invalid");
  }
});

repeatPasswordInput.addEventListener("input", function () {
  if (
    repeatPasswordInput.value == passwordInput.value &&
    repeatPasswordInput.value !== "" &&
    checkPasswordStrength(passwordInput.value)
  ) {
    repeatPasswordInput.classList.remove("is-invalid");
    repeatPasswordInput.classList.add("is-valid");
    valid_password = true;
  } else {
    repeatPasswordInput.classList.remove("is-valid");
    repeatPasswordInput.classList.add("is-invalid");
    valid_password = false;
  }
});

function checkPasswordStrength(password) {
  const minLength = 8;
  const hasUppercase = /[A-Z]/.test(password);
  const hasLowercase = /[a-z]/.test(password);
  const hasNumber = /\d/.test(password);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

  if (
    password.length < minLength ||
    !hasUppercase ||
    !hasLowercase ||
    !hasNumber ||
    !hasSpecialChar
  ) {
    return false;
  } else {
    return true;
  }
}

registerForm.addEventListener("submit", function (e) {
  if (
    !valid_firstName ||
    !valid_lastName ||
    !valid_userName ||
    !valid_password ||
    !valid_mail
  ) {
    e.preventDefault();
    alert("Please fill all the fields of the form!!! >:(")
  }
});
