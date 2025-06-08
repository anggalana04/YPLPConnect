document.addEventListener('DOMContentLoaded', function () {
  const contactBox1 = document.querySelector('.contact-box1');
  const contactBox2 = document.querySelector('.contact-box2');

  function isInView(el) {
    const rect = el.getBoundingClientRect();
    return rect.top < window.innerHeight - 50 && rect.bottom > 50;
  }

  function checkContactInView() {
    const inView1 = isInView(contactBox1);
    const inView2 = isInView(contactBox2);

    if (inView1) {
      if (!contactBox1.classList.contains('show')) {
        contactBox1.classList.add('show');
      }
    } else {
      contactBox1.classList.remove('show'); // Reset untuk animasi ulang
    }

    if (inView2) {
      if (!contactBox2.classList.contains('show')) {
        contactBox2.classList.add('show');
      }
    } else {
      contactBox2.classList.remove('show'); // Reset untuk animasi ulang
    }
  }

  window.addEventListener('scroll', checkContactInView);
  checkContactInView(); // jalankan saat halaman pertama kali dibuka
});
