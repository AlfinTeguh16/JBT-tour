<script>
  document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll('.only-number');
  
    inputs.forEach(function (input) {
      input.addEventListener('keypress', function (e) {
        const charCode = e.which ? e.which : e.keyCode;
        if (charCode < 48 || charCode > 57) {
          e.preventDefault();
        }
      });
  
      input.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      });
  
      const form = input.closest('form');
      if (form) {
        form.addEventListener('submit', function () {
          input.value = input.value.replace(/\./g, '');
        });
      }
  
      let value = input.value.replace(/\D/g, '');
      input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
  });
  </script>
  