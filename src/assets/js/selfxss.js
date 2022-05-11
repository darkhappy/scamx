// Show a warning in the console warning users to not paste random code into the console.
// This is a self-xss prevention script.

console.log(
  "%c" + "HOLD UP!",
  "color: #5865f2; -webkit-text-stroke: 2px black; font-size: 72px; font-weight: bold;"
);
console.log(
  "%c" +
    "If someone told you to copy/paste something here you have an 11/10 chance you're being scammed.",
  "font-size: 16px;"
);
console.log(
  "%c" +
    "Pasting anything in here could give attackers access to your ScamX account.",
  "font-size: 18px; font-weight: bold; color: red;"
);
