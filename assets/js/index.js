document.addEventListener('DOMContentLoaded', function () {
  const modalLogin = document.getElementById('modal-login');
  const modalRegistro = document.getElementById('modal-registro');

  if (document.body.dataset.alert) {
    alert(document.body.dataset.alert);
  }

  function openModal(modal) {
    modal.classList.add('active');
  }

  function closeModal(modal) {
    modal.classList.remove('active');
  }

  document.getElementById('btn-login').addEventListener('click', function () {
    openModal(modalLogin);
  });

  document.getElementById('btn-registro').addEventListener('click', function () {
    openModal(modalRegistro);
  });

  document.querySelectorAll('.modal-close').forEach(function (btn) {
    btn.addEventListener('click', function () {
      closeModal(document.getElementById(btn.dataset.modal));
    });
  });

  [modalLogin, modalRegistro].forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeModal(overlay);
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeModal(modalLogin);
      closeModal(modalRegistro);
    }
  });

  const switchRegistro = document.getElementById('switch-registro');
  const switchLogin = document.getElementById('switch-login');

  if (switchRegistro) {
    switchRegistro.addEventListener('click', function (e) {
      e.preventDefault();
      closeModal(modalLogin);
      openModal(modalRegistro);
    });
  }

  if (switchLogin) {
    switchLogin.addEventListener('click', function (e) {
      e.preventDefault();
      closeModal(modalRegistro);
      openModal(modalLogin);
    });
  }
});
