// main.js

document.addEventListener('DOMContentLoaded', function () {
  console.log("TrendTalk JS loaded!");

  // Live toggle for any extra interactivity (e.g., Auto resize textareas)
  const textareas = document.querySelectorAll('textarea');
  textareas.forEach(t => {
    t.setAttribute('rows', '3');
    t.addEventListener('input', function () {
      this.style.height = 'auto';
      this.style.height = this.scrollHeight + 'px';
    });
  });
});