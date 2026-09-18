(() => {
  'use strict';

  const storagePrefix = 'dzn-portal-announcement:';

  document.querySelectorAll('[data-dzn-announcement]').forEach((announcement) => {
    const id = announcement.dataset.dznAnnouncement;
    let dismissed = false;

    if (id) {
      try {
        dismissed = window.sessionStorage.getItem(`${storagePrefix}${id}`) === 'dismissed';
      } catch (error) {
        dismissed = false;
      }
    }

    if (dismissed) announcement.hidden = true;

    announcement.querySelector('[data-dzn-announcement-dismiss]')?.addEventListener('click', () => {
      announcement.hidden = true;
      if (!id) return;

      try {
        window.sessionStorage.setItem(`${storagePrefix}${id}`, 'dismissed');
      } catch (error) {
        // Dismissal remains useful for this render even when storage is blocked.
      }
    });
  });

  const openDialog = (dialog) => {
    if (!dialog) return;
    if (typeof dialog.showModal === 'function') {
      dialog.showModal();
    } else {
      dialog.setAttribute('open', '');
    }
  };

  const closeDialog = (dialog) => {
    if (!dialog) return;
    if (typeof dialog.close === 'function') {
      dialog.close();
    } else {
      dialog.removeAttribute('open');
    }
  };

  document.querySelectorAll('[data-dzn-dialog-open]').forEach((button) => {
    button.addEventListener('click', () => {
      openDialog(document.getElementById(button.dataset.dznDialogOpen));
    });
  });

  document.querySelectorAll('.dzn-portal-dialog').forEach((dialog) => {
    dialog.querySelector('[data-dzn-dialog-close]')?.addEventListener('click', () => closeDialog(dialog));
    dialog.addEventListener('click', (event) => {
      if (event.target === dialog) closeDialog(dialog);
    });
  });

  document.querySelectorAll('[data-dzn-presentation-action]').forEach((button) => {
    button.addEventListener('click', () => {
      const statusId = button.getAttribute('aria-describedby');
      const status = statusId ? document.getElementById(statusId) : null;
      if (status) {
        status.textContent = 'این کنترل نمایشی است؛ هیچ اطلاعاتی ثبت یا ارسال نشد.';
      }
    });
  });
})();
