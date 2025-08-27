document.addEventListener('DOMContentLoaded', function () {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const productItems = document.querySelectorAll('.product');

  filterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const selectedCat = button.getAttribute('data-cat');
      productItems.forEach(item => {
        item.style.display = selectedCat === 'all' || item.classList.contains('cat-' + selectedCat) ? 'block' : 'none';
      });
    });
  });

  const suggestBtn = document.getElementById('suggest-product-btn');
  const suggestForm = document.getElementById('suggest-form');
  if (suggestBtn && suggestForm) {
    suggestBtn.addEventListener('click', () => {
      suggestForm.style.display = suggestForm.style.display === 'none' ? 'block' : 'none';
    });
  }
});
